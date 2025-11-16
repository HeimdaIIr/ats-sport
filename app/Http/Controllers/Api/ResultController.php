<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChronoFront\Result;
use App\Models\ChronoFront\Entrant;
use App\Models\ChronoFront\Race;
use App\Services\ChronoFront\ResultsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    protected ResultsService $resultsService;

    public function __construct(ResultsService $resultsService)
    {
        $this->resultsService = $resultsService;
    }
    /**
     * Display results for a specific race
     */
    public function byRace(int $raceId): JsonResponse
    {
        $results = Result::where('race_id', $raceId)
            ->with(['entrant.category', 'wave'])
            ->orderBy('position')
            ->get();

        return response()->json($results);
    }

    /**
     * Add a new timing result
     */
    public function addTime(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'race_id' => 'required|exists:races,id',
            'entrant_id' => 'sometimes|exists:entrants,id',
            'bib_number' => 'sometimes|string',
            'rfid_tag' => 'sometimes|string',
            'raw_time' => 'nullable|date',
            'is_manual' => 'boolean',
        ]);

        // Find entrant by bib_number or rfid_tag if entrant_id not provided
        if (!isset($validated['entrant_id'])) {
            $entrant = null;

            if (isset($validated['bib_number'])) {
                $entrant = Entrant::where('bib_number', $validated['bib_number'])
                    ->where('race_id', $validated['race_id'])
                    ->first();
            } elseif (isset($validated['rfid_tag'])) {
                $entrant = Entrant::where('rfid_tag', $validated['rfid_tag'])
                    ->where('race_id', $validated['race_id'])
                    ->first();
            }

            if (!$entrant) {
                return response()->json([
                    'message' => 'Participant non trouvé'
                ], 404);
            }

            $validated['entrant_id'] = $entrant->id;
            $validated['rfid_tag'] = $entrant->rfid_tag;
        } else {
            $entrant = Entrant::findOrFail($validated['entrant_id']);
            $validated['rfid_tag'] = $entrant->rfid_tag;
        }

        // Set raw_time to now if not provided
        if (!isset($validated['raw_time'])) {
            $validated['raw_time'] = now();
        }

        // Get wave_id from entrant
        $validated['wave_id'] = $entrant->wave_id;

        // Determine lap number
        $lapNumber = Result::where('race_id', $validated['race_id'])
            ->where('entrant_id', $validated['entrant_id'])
            ->max('lap_number') ?? 0;

        $validated['lap_number'] = $lapNumber + 1;
        $validated['is_manual'] = $validated['is_manual'] ?? true;

        // Create result
        $result = Result::create($validated);

        // Calculate time and speed
        $this->calculateResult($result);

        $result->load(['entrant.category', 'wave']);

        return response()->json([
            'message' => 'Temps ajouté avec succès',
            'result' => $result
        ], 201);
    }

    /**
     * Recalculate all positions for a race
     */
    public function recalculatePositions(int $raceId): JsonResponse
    {
        $race = Race::findOrFail($raceId);

        DB::beginTransaction();

        try {
            // Get all results for this race, grouped by entrant
            // For races with multiple laps, take best time or last lap depending on race type
            $results = Result::where('race_id', $raceId)
                ->where('status', 'V')
                ->with(['entrant.category'])
                ->get()
                ->groupBy('entrant_id')
                ->map(function ($entrantResults) use ($race) {
                    // For best_time races, keep best time
                    // Otherwise keep last lap
                    if ($race->best_time) {
                        return $entrantResults->sortBy('calculated_time')->first();
                    } else {
                        return $entrantResults->sortByDesc('lap_number')->first();
                    }
                })
                ->sortBy('calculated_time')
                ->values();

            // Calculate overall positions
            $position = 1;
            foreach ($results as $result) {
                $result->update(['position' => $position++]);
            }

            // Calculate category positions
            $resultsByCategory = $results->groupBy(function ($result) {
                return $result->entrant->category_id;
            });

            foreach ($resultsByCategory as $categoryId => $categoryResults) {
                $categoryPosition = 1;
                foreach ($categoryResults as $result) {
                    $result->update(['category_position' => $categoryPosition++]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Positions recalculées avec succès',
                'total_results' => $results->count()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Erreur lors du recalcul des positions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export results to CSV
     */
    public function export(int $raceId): \Illuminate\Http\Response
    {
        $race = Race::with('event')->findOrFail($raceId);

        $results = Result::where('race_id', $raceId)
            ->with(['entrant.category'])
            ->orderBy('position')
            ->get();

        $csv = "Position,Dossard,Nom,Prénom,Sexe,Catégorie,Club,Temps,Vitesse,Position Catégorie,Statut\n";

        foreach ($results as $result) {
            $csv .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s\n",
                $result->position ?? 'N/A',
                $result->entrant->bib_number ?? '',
                $result->entrant->lastname ?? '',
                $result->entrant->firstname ?? '',
                $result->entrant->gender ?? '',
                $result->entrant->category->name ?? '',
                $result->entrant->club ?? '',
                $result->formatted_time ?? '',
                $result->speed ? number_format($result->speed, 2) . ' km/h' : '',
                $result->category_position ?? 'N/A',
                $result->status
            );
        }

        $filename = sprintf(
            'resultats_%s_%s_%s.csv',
            $race->event->name ?? 'event',
            $race->name,
            now()->format('Y-m-d')
        );

        return response($csv, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    /**
     * Update a result
     */
    public function update(Request $request, Result $result): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'sometimes|in:V,DNS,DNF,DSQ,NS',
            'raw_time' => 'sometimes|date',
        ]);

        $result->update($validated);

        if (isset($validated['raw_time'])) {
            $this->calculateResult($result);
        }

        return response()->json($result);
    }

    /**
     * Delete a result
     */
    public function destroy(Result $result): JsonResponse
    {
        $result->delete();
        return response()->json(['message' => 'Result deleted successfully']);
    }

    /**
     * Calculate time and speed for a result
     */
    private function calculateResult(Result $result): void
    {
        $result->load(['wave', 'race', 'entrant']);

        // Calculate time from wave start
        if ($result->wave && $result->wave->start_time) {
            $result->calculateTime();
        }

        // Calculate speed
        if ($result->race && $result->race->distance > 0 && $result->calculated_time > 0) {
            $result->calculateSpeed($result->race->distance);
        }

        // Calculate lap time if this is not the first lap
        if ($result->lap_number > 1) {
            $previousLap = Result::where('race_id', $result->race_id)
                ->where('entrant_id', $result->entrant_id)
                ->where('lap_number', $result->lap_number - 1)
                ->first();

            if ($previousLap && $previousLap->calculated_time && $result->calculated_time) {
                $result->lap_time = $result->calculated_time - $previousLap->calculated_time;
            }
        } else {
            $result->lap_time = $result->calculated_time;
        }

        $result->save();
    }

    /**
     * Calculer les résultats d'une course (ChronoFront)
     * Utilise les détections RFID pour calculer les temps et positions
     *
     * POST /api/results/race/{raceId}/calculate
     */
    public function calculateResults(Request $request, int $raceId): JsonResponse
    {
        $forceRecalculate = $request->input('force', false);

        $stats = $this->resultsService->calculateRaceResults($raceId, $forceRecalculate);

        if (!$stats['success']) {
            return response()->json([
                'success' => false,
                'message' => $stats['message']
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => "Résultats calculés : {$stats['calculated']} participants",
            'stats' => $stats
        ]);
    }

    /**
     * Obtenir le classement scratch (général)
     *
     * GET /api/results/race/{raceId}/scratch?limit=100
     */
    public function scratchRanking(int $raceId, Request $request): JsonResponse
    {
        $limit = min($request->input('limit', 100), 1000);

        $results = $this->resultsService->getScratchRanking($raceId, $limit);

        return response()->json([
            'success' => true,
            'race_id' => $raceId,
            'ranking_type' => 'scratch',
            'count' => $results->count(),
            'results' => $results->map(function ($result) {
                return [
                    'position' => $result->position_scratch,
                    'bib_number' => $result->entrant->bib_number,
                    'firstname' => $result->entrant->firstname,
                    'lastname' => $result->entrant->lastname,
                    'gender' => $result->entrant->gender,
                    'category' => $result->entrant->category->name ?? null,
                    'club' => $result->entrant->club,
                    'race_time' => $this->resultsService->formatTime($result->race_time),
                    'race_time_seconds' => $result->race_time,
                    'finish_time' => $result->finish_time->toDateTimeString(),
                ];
            })
        ]);
    }

    /**
     * Obtenir le classement par sexe
     *
     * GET /api/results/race/{raceId}/gender/{gender}?limit=100
     */
    public function genderRanking(int $raceId, string $gender, Request $request): JsonResponse
    {
        if (!in_array($gender, ['M', 'F'])) {
            return response()->json([
                'success' => false,
                'message' => 'Sexe invalide (M ou F attendu)'
            ], 400);
        }

        $limit = min($request->input('limit', 100), 1000);

        $results = $this->resultsService->getGenderRanking($raceId, $gender, $limit);

        return response()->json([
            'success' => true,
            'race_id' => $raceId,
            'ranking_type' => 'gender',
            'gender' => $gender,
            'count' => $results->count(),
            'results' => $results->map(function ($result) {
                return [
                    'position_gender' => $result->position_gender,
                    'position_scratch' => $result->position_scratch,
                    'bib_number' => $result->entrant->bib_number,
                    'firstname' => $result->entrant->firstname,
                    'lastname' => $result->entrant->lastname,
                    'category' => $result->entrant->category->name ?? null,
                    'club' => $result->entrant->club,
                    'race_time' => $this->resultsService->formatTime($result->race_time),
                    'race_time_seconds' => $result->race_time,
                ];
            })
        ]);
    }

    /**
     * Obtenir le classement par catégorie
     *
     * GET /api/results/race/{raceId}/category/{categoryId}?limit=100
     */
    public function categoryRanking(int $raceId, int $categoryId, Request $request): JsonResponse
    {
        $limit = min($request->input('limit', 100), 1000);

        $results = $this->resultsService->getCategoryRanking($raceId, $categoryId, $limit);

        return response()->json([
            'success' => true,
            'race_id' => $raceId,
            'ranking_type' => 'category',
            'category_id' => $categoryId,
            'count' => $results->count(),
            'results' => $results->map(function ($result) {
                return [
                    'position_category' => $result->position_category,
                    'position_scratch' => $result->position_scratch,
                    'bib_number' => $result->entrant->bib_number,
                    'firstname' => $result->entrant->firstname,
                    'lastname' => $result->entrant->lastname,
                    'gender' => $result->entrant->gender,
                    'club' => $result->entrant->club,
                    'race_time' => $this->resultsService->formatTime($result->race_time),
                    'race_time_seconds' => $result->race_time,
                ];
            })
        ]);
    }

    /**
     * Obtenir les statistiques d'une course
     *
     * GET /api/results/race/{raceId}/statistics
     */
    public function statistics(int $raceId): JsonResponse
    {
        $stats = $this->resultsService->getRaceStats($raceId);

        return response()->json([
            'success' => true,
            'race_id' => $raceId,
            'statistics' => $stats
        ]);
    }
}
