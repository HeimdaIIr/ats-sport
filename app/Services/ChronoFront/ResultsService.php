<?php

namespace App\Services\ChronoFront;

use App\Models\ChronoFront\Race;
use App\Models\ChronoFront\Entrant;
use App\Models\ChronoFront\RaceTime;
use App\Models\ChronoFront\TimingPoint;
use App\Models\ChronoFront\Result;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service de calcul des résultats et classements
 *
 * Calcule:
 * - Temps de course (arrivée - départ)
 * - Position scratch (classement général)
 * - Position gender (classement par sexe)
 * - Position category (classement par catégorie)
 */
class ResultsService
{
    /**
     * Calculer les résultats pour une course complète
     *
     * @param int $raceId ID de la course
     * @param bool $forceRecalculate Forcer le recalcul même si résultats existent
     * @return array Statistiques du calcul
     */
    public function calculateRaceResults(int $raceId, bool $forceRecalculate = false): array
    {
        $race = Race::findOrFail($raceId);

        // Trouver les timing points de départ et arrivée
        $startPoint = TimingPoint::where('race_id', $raceId)
            ->where('point_type', 'start')
            ->orderBy('order_number')
            ->first();

        $finishPoint = TimingPoint::where('race_id', $raceId)
            ->where('point_type', 'finish')
            ->orderBy('order_number', 'desc')
            ->first();

        if (!$startPoint || !$finishPoint) {
            Log::error("Points de chronométrage manquants", [
                'race_id' => $raceId,
                'has_start' => $startPoint !== null,
                'has_finish' => $finishPoint !== null
            ]);

            return [
                'success' => false,
                'message' => 'Points de chronométrage départ/arrivée manquants',
                'calculated' => 0
            ];
        }

        // Si forceRecalculate, supprimer les résultats existants
        if ($forceRecalculate) {
            Result::where('race_id', $raceId)->delete();
        }

        // Récupérer tous les participants qui ont franchi la ligne d'arrivée
        $finishers = RaceTime::where('timing_point_id', $finishPoint->id)
            ->with(['entrant' => function ($query) use ($raceId) {
                $query->where('race_id', $raceId)->with('category');
            }])
            ->orderBy('detection_time')
            ->get();

        $calculated = 0;
        $errors = 0;
        $resultData = [];

        foreach ($finishers as $finishTime) {
            if (!$finishTime->entrant) {
                $errors++;
                continue;
            }

            // Chercher le temps de départ
            $startTime = RaceTime::where('timing_point_id', $startPoint->id)
                ->where('entrant_id', $finishTime->entrant_id)
                ->orderBy('detection_time')
                ->first();

            if (!$startTime) {
                Log::warning("Temps de départ manquant", [
                    'entrant_id' => $finishTime->entrant_id,
                    'bib_number' => $finishTime->entrant->bib_number
                ]);
                $errors++;
                continue;
            }

            // Calculer le temps de course en secondes
            $raceTimeSeconds = $finishTime->detection_time->diffInSeconds($startTime->detection_time);

            // Créer ou mettre à jour le résultat
            $result = Result::updateOrCreate(
                [
                    'race_id' => $raceId,
                    'entrant_id' => $finishTime->entrant_id
                ],
                [
                    'finish_time' => $finishTime->detection_time,
                    'race_time' => $raceTimeSeconds,
                    'status' => 'finished',
                    // Les positions seront calculées après
                    'position_scratch' => 0,
                    'position_gender' => 0,
                    'position_category' => 0
                ]
            );

            $resultData[] = [
                'result_id' => $result->id,
                'entrant_id' => $finishTime->entrant_id,
                'gender' => $finishTime->entrant->gender,
                'category_id' => $finishTime->entrant->category_id,
                'race_time' => $raceTimeSeconds
            ];

            $calculated++;
        }

        // Calculer les positions
        $this->calculatePositions($raceId, $resultData);

        return [
            'success' => true,
            'race_id' => $raceId,
            'calculated' => $calculated,
            'errors' => $errors,
            'total_finishers' => count($resultData)
        ];
    }

    /**
     * Calculer les positions (scratch, gender, category)
     *
     * @param int $raceId ID de la course
     * @param array $resultData Données des résultats
     * @return void
     */
    protected function calculatePositions(int $raceId, array $resultData): void
    {
        // Trier par temps de course
        usort($resultData, fn($a, $b) => $a['race_time'] <=> $b['race_time']);

        // 1. Position SCRATCH (général)
        foreach ($resultData as $index => $data) {
            Result::where('id', $data['result_id'])
                ->update(['position_scratch' => $index + 1]);
        }

        // 2. Position GENDER (par sexe)
        $genderGroups = [];
        foreach ($resultData as $data) {
            $genderGroups[$data['gender']][] = $data;
        }

        foreach ($genderGroups as $gender => $group) {
            foreach ($group as $index => $data) {
                Result::where('id', $data['result_id'])
                    ->update(['position_gender' => $index + 1]);
            }
        }

        // 3. Position CATEGORY (par catégorie)
        $categoryGroups = [];
        foreach ($resultData as $data) {
            if ($data['category_id']) {
                $categoryGroups[$data['category_id']][] = $data;
            }
        }

        foreach ($categoryGroups as $categoryId => $group) {
            foreach ($group as $index => $data) {
                Result::where('id', $data['result_id'])
                    ->update(['position_category' => $index + 1]);
            }
        }

        Log::info("Positions calculées", [
            'race_id' => $raceId,
            'total_results' => count($resultData),
            'genders' => count($genderGroups),
            'categories' => count($categoryGroups)
        ]);
    }

    /**
     * Calculer le résultat pour un participant spécifique
     *
     * @param int $entrantId ID du participant
     * @return Result|null
     */
    public function calculateEntrantResult(int $entrantId): ?Result
    {
        $entrant = Entrant::with('race')->findOrFail($entrantId);
        $raceId = $entrant->race_id;

        // Trouver les timing points
        $startPoint = TimingPoint::where('race_id', $raceId)
            ->where('point_type', 'start')
            ->first();

        $finishPoint = TimingPoint::where('race_id', $raceId)
            ->where('point_type', 'finish')
            ->first();

        if (!$startPoint || !$finishPoint) {
            return null;
        }

        // Chercher les temps
        $startTime = RaceTime::where('timing_point_id', $startPoint->id)
            ->where('entrant_id', $entrantId)
            ->orderBy('detection_time')
            ->first();

        $finishTime = RaceTime::where('timing_point_id', $finishPoint->id)
            ->where('entrant_id', $entrantId)
            ->orderBy('detection_time')
            ->first();

        if (!$startTime || !$finishTime) {
            return null;
        }

        // Calculer le temps
        $raceTimeSeconds = $finishTime->detection_time->diffInSeconds($startTime->detection_time);

        // Créer/mettre à jour le résultat
        $result = Result::updateOrCreate(
            [
                'race_id' => $raceId,
                'entrant_id' => $entrantId
            ],
            [
                'finish_time' => $finishTime->detection_time,
                'race_time' => $raceTimeSeconds,
                'status' => 'finished'
            ]
        );

        // Recalculer toutes les positions de la course
        $this->recalculatePositions($raceId);

        return $result->fresh();
    }

    /**
     * Recalculer uniquement les positions d'une course (sans recalculer les temps)
     *
     * @param int $raceId ID de la course
     * @return int Nombre de résultats mis à jour
     */
    public function recalculatePositions(int $raceId): int
    {
        $results = Result::where('race_id', $raceId)
            ->with('entrant')
            ->orderBy('race_time')
            ->get();

        if ($results->isEmpty()) {
            return 0;
        }

        $resultData = $results->map(function ($result) {
            return [
                'result_id' => $result->id,
                'entrant_id' => $result->entrant_id,
                'gender' => $result->entrant->gender,
                'category_id' => $result->entrant->category_id,
                'race_time' => $result->race_time
            ];
        })->toArray();

        $this->calculatePositions($raceId, $resultData);

        return count($resultData);
    }

    /**
     * Obtenir le classement scratch d'une course
     *
     * @param int $raceId ID de la course
     * @param int $limit Nombre de résultats
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getScratchRanking(int $raceId, int $limit = 100)
    {
        return Result::where('race_id', $raceId)
            ->with(['entrant', 'entrant.category'])
            ->orderBy('position_scratch')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir le classement par sexe
     *
     * @param int $raceId ID de la course
     * @param string $gender M ou F
     * @param int $limit Nombre de résultats
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getGenderRanking(int $raceId, string $gender, int $limit = 100)
    {
        return Result::where('race_id', $raceId)
            ->whereHas('entrant', function ($query) use ($gender) {
                $query->where('gender', $gender);
            })
            ->with(['entrant', 'entrant.category'])
            ->orderBy('position_gender')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir le classement par catégorie
     *
     * @param int $raceId ID de la course
     * @param int $categoryId ID de la catégorie
     * @param int $limit Nombre de résultats
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCategoryRanking(int $raceId, int $categoryId, int $limit = 100)
    {
        return Result::where('race_id', $raceId)
            ->whereHas('entrant', function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->with(['entrant', 'entrant.category'])
            ->orderBy('position_category')
            ->limit($limit)
            ->get();
    }

    /**
     * Formater un temps en secondes en format lisible (HH:MM:SS)
     *
     * @param int $seconds Temps en secondes
     * @return string Format HH:MM:SS
     */
    public function formatTime(int $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
    }

    /**
     * Obtenir les statistiques d'une course
     *
     * @param int $raceId ID de la course
     * @return array
     */
    public function getRaceStats(int $raceId): array
    {
        $totalEntrants = Entrant::where('race_id', $raceId)->count();
        $finishers = Result::where('race_id', $raceId)
            ->where('status', 'finished')
            ->count();

        $avgTime = Result::where('race_id', $raceId)
            ->where('status', 'finished')
            ->avg('race_time');

        $fastestTime = Result::where('race_id', $raceId)
            ->where('status', 'finished')
            ->min('race_time');

        $slowestTime = Result::where('race_id', $raceId)
            ->where('status', 'finished')
            ->max('race_time');

        return [
            'total_entrants' => $totalEntrants,
            'finishers' => $finishers,
            'dnf' => $totalEntrants - $finishers,
            'finish_rate' => $totalEntrants > 0 ? round(($finishers / $totalEntrants) * 100, 2) : 0,
            'avg_time' => $avgTime ? $this->formatTime((int)$avgTime) : null,
            'fastest_time' => $fastestTime ? $this->formatTime($fastestTime) : null,
            'slowest_time' => $slowestTime ? $this->formatTime($slowestTime) : null,
        ];
    }
}
