<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChronoFront\Entrant;
use App\Models\ChronoFront\RaceTime;
use App\Models\ChronoFront\TimingPoint;
use App\Events\ChronoFront\RaceTimeRecorded;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

/**
 * Controller pour la saisie manuelle des temps de chronométrage
 * Utilisé comme backup quand le système RFID n'est pas disponible
 */
class ManualTimingController extends Controller
{
    /**
     * Enregistrer un temps manuel par numéro de dossard
     *
     * POST /api/manual-timing/record
     * Body: {
     *   "bib_number": "123",
     *   "timing_point_id": 1,
     *   "detection_time": "2025-03-16 14:30:25" // Optional, now() if not provided
     * }
     */
    public function recordByBibNumber(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'bib_number' => 'required|integer',
            'timing_point_id' => 'required|integer|exists:timing_points,id',
            'detection_time' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        $timingPoint = TimingPoint::findOrFail($request->input('timing_point_id'));

        // Chercher le participant par dossard ET race_id
        $entrant = Entrant::where('bib_number', $request->input('bib_number'))
            ->where('race_id', $timingPoint->race_id)
            ->first();

        if (!$entrant) {
            return response()->json([
                'success' => false,
                'message' => "Dossard {$request->input('bib_number')} introuvable dans cette course"
            ], 404);
        }

        // Utiliser le temps fourni ou now()
        $detectionTime = $request->input('detection_time')
            ? Carbon::parse($request->input('detection_time'))
            : now();

        // Vérifier doublons (dans les 5 secondes)
        $existingDetection = RaceTime::where('entrant_id', $entrant->id)
            ->where('timing_point_id', $timingPoint->id)
            ->where('detection_time', '>=', $detectionTime->copy()->subSeconds(5))
            ->where('detection_time', '<=', $detectionTime->copy()->addSeconds(5))
            ->first();

        if ($existingDetection) {
            return response()->json([
                'success' => false,
                'message' => 'Temps déjà enregistré pour ce participant',
                'existing_detection' => [
                    'id' => $existingDetection->id,
                    'detection_time' => $existingDetection->detection_time->toDateTimeString()
                ]
            ], 409);
        }

        // Créer l'enregistrement
        $raceTime = RaceTime::create([
            'entrant_id' => $entrant->id,
            'timing_point_id' => $timingPoint->id,
            'detection_time' => $detectionTime,
            'detection_type' => 'manual',
            'rfid_tag_read' => null
        ]);

        // Broadcaster l'événement
        event(new RaceTimeRecorded($raceTime));

        return response()->json([
            'success' => true,
            'message' => 'Temps enregistré avec succès',
            'data' => [
                'race_time_id' => $raceTime->id,
                'entrant' => [
                    'id' => $entrant->id,
                    'bib_number' => $entrant->bib_number,
                    'firstname' => $entrant->firstname,
                    'lastname' => $entrant->lastname,
                    'gender' => $entrant->gender
                ],
                'timing_point' => $timingPoint->name,
                'detection_time' => $raceTime->detection_time->toDateTimeString(),
                'detection_type' => 'manual'
            ]
        ], 201);
    }

    /**
     * Enregistrer plusieurs temps manuels en batch
     *
     * POST /api/manual-timing/batch
     * Body: {
     *   "timing_point_id": 1,
     *   "bib_numbers": [1, 2, 3, 4, 5],
     *   "start_time": "2025-03-16 14:30:00", // Optional
     *   "interval_seconds": 5 // Optional, délai entre chaque participant
     * }
     */
    public function recordBatch(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'timing_point_id' => 'required|integer|exists:timing_points,id',
            'bib_numbers' => 'required|array|min:1|max:500',
            'bib_numbers.*' => 'required|integer',
            'start_time' => 'nullable|date',
            'interval_seconds' => 'nullable|integer|min:0|max:3600'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $timingPoint = TimingPoint::findOrFail($request->input('timing_point_id'));
        $startTime = $request->input('start_time')
            ? Carbon::parse($request->input('start_time'))
            : now();
        $interval = $request->input('interval_seconds', 0);

        $stats = ['success' => 0, 'errors' => 0, 'details' => []];
        $currentTime = $startTime->copy();

        foreach ($request->input('bib_numbers') as $bibNumber) {
            $entrant = Entrant::where('bib_number', $bibNumber)
                ->where('race_id', $timingPoint->race_id)
                ->first();

            if (!$entrant) {
                $stats['errors']++;
                $stats['details'][] = "Dossard {$bibNumber} introuvable";
                continue;
            }

            // Vérifier doublons
            $exists = RaceTime::where('entrant_id', $entrant->id)
                ->where('timing_point_id', $timingPoint->id)
                ->where('detection_time', '>=', $currentTime->copy()->subSeconds(5))
                ->exists();

            if ($exists) {
                $stats['errors']++;
                $stats['details'][] = "Dossard {$bibNumber} déjà enregistré";
                continue;
            }

            // Créer l'enregistrement
            $raceTime = RaceTime::create([
                'entrant_id' => $entrant->id,
                'timing_point_id' => $timingPoint->id,
                'detection_time' => $currentTime->copy(),
                'detection_type' => 'manual',
                'rfid_tag_read' => null
            ]);

            event(new RaceTimeRecorded($raceTime));

            $stats['success']++;
            $currentTime->addSeconds($interval);
        }

        return response()->json([
            'success' => true,
            'message' => "Batch traité : {$stats['success']} enregistrés, {$stats['errors']} erreurs",
            'stats' => $stats
        ]);
    }

    /**
     * Obtenir les derniers temps enregistrés pour un point
     *
     * GET /api/manual-timing/timing-point/{timingPointId}/recent?limit=20
     */
    public function recentDetections(int $timingPointId, Request $request): JsonResponse
    {
        $limit = min($request->input('limit', 20), 100);

        $detections = RaceTime::where('timing_point_id', $timingPointId)
            ->with('entrant')
            ->orderBy('detection_time', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'timing_point_id' => $timingPointId,
            'count' => $detections->count(),
            'detections' => $detections->map(function ($raceTime) {
                return [
                    'id' => $raceTime->id,
                    'bib_number' => $raceTime->entrant->bib_number,
                    'name' => "{$raceTime->entrant->firstname} {$raceTime->entrant->lastname}",
                    'gender' => $raceTime->entrant->gender,
                    'detection_time' => $raceTime->detection_time->toDateTimeString(),
                    'detection_type' => $raceTime->detection_type,
                    'time_ago' => $raceTime->detection_time->diffForHumans()
                ];
            })
        ]);
    }

    /**
     * Supprimer une détection manuelle
     *
     * DELETE /api/manual-timing/detection/{detectionId}
     */
    public function deleteDetection(int $detectionId): JsonResponse
    {
        $raceTime = RaceTime::where('id', $detectionId)
            ->where('detection_type', 'manual')
            ->first();

        if (!$raceTime) {
            return response()->json([
                'success' => false,
                'message' => 'Détection manuelle introuvable'
            ], 404);
        }

        $raceTime->delete();

        return response()->json([
            'success' => true,
            'message' => 'Détection supprimée avec succès'
        ]);
    }

    /**
     * Rechercher un participant par dossard (pour validation avant saisie)
     *
     * GET /api/manual-timing/lookup/bib/{bibNumber}/race/{raceId}
     */
    public function lookupByBib(int $bibNumber, int $raceId): JsonResponse
    {
        $entrant = Entrant::where('bib_number', $bibNumber)
            ->where('race_id', $raceId)
            ->with('category')
            ->first();

        if (!$entrant) {
            return response()->json([
                'success' => false,
                'message' => 'Participant introuvable'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'entrant' => [
                'id' => $entrant->id,
                'bib_number' => $entrant->bib_number,
                'firstname' => $entrant->firstname,
                'lastname' => $entrant->lastname,
                'gender' => $entrant->gender,
                'category' => $entrant->category->name ?? null,
                'club' => $entrant->club
            ]
        ]);
    }
}
