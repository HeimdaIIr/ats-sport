<?php

namespace App\Services\ChronoFront;

use App\Models\ChronoFront\Entrant;
use App\Models\ChronoFront\RaceTime;
use App\Models\ChronoFront\TimingPoint;
use App\Events\ChronoFront\RaceTimeRecorded;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Service de gestion des détections RFID SportLab 2.0
 *
 * Format RFID: [TAG]:aYYYYMMDDHHMMSSmmm
 * Exemple: [2000001]:a20250316143025123
 * - TAG: 2000001 (numéro RFID du participant)
 * - a: préfixe (antenna identifier)
 * - DateTime: 2025-03-16 14:30:25.123
 */
class RfidService
{
    /**
     * Parser une détection RFID au format SportLab 2.0
     *
     * @param string $rfidString Format: [TAG]:aYYYYMMDDHHMMSSmmm
     * @return array|null ['tag' => string, 'timestamp' => Carbon] ou null si invalide
     */
    public function parseRfidDetection(string $rfidString): ?array
    {
        // Regex pour parser le format: [TAG]:aYYYYMMDDHHMMSSmmm
        // Groupe 1: TAG (digits)
        // Groupe 2: a (antenna prefix)
        // Groupe 3: YYYYMMDD (date)
        // Groupe 4: HHMMSS (time)
        // Groupe 5: mmm (milliseconds)
        $pattern = '/\[(\d+)\]:a(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})(\d{3})/';

        if (!preg_match($pattern, $rfidString, $matches)) {
            Log::warning("Format RFID invalide", ['rfid_string' => $rfidString]);
            return null;
        }

        $tag = $matches[1];
        $year = $matches[2];
        $month = $matches[3];
        $day = $matches[4];
        $hour = $matches[5];
        $minute = $matches[6];
        $second = $matches[7];
        $millisecond = $matches[8];

        try {
            // Créer le timestamp Carbon avec millisecondes
            $timestamp = Carbon::createFromFormat(
                'Y-m-d H:i:s.u',
                "{$year}-{$month}-{$day} {$hour}:{$minute}:{$second}.{$millisecond}000"
            );

            return [
                'tag' => $tag,
                'timestamp' => $timestamp,
                'raw' => $rfidString
            ];
        } catch (\Exception $e) {
            Log::error("Erreur parsing date RFID", [
                'rfid_string' => $rfidString,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Enregistrer une détection RFID dans la base de données
     *
     * @param string $rfidString Format SportLab: [TAG]:aYYYYMMDDHHMMSSmmm
     * @param int $timingPointId ID du point de chronométrage (départ/arrivée/intermédiaire)
     * @return RaceTime|null L'enregistrement créé ou null si erreur
     */
    public function recordDetection(string $rfidString, int $timingPointId): ?RaceTime
    {
        // Parser la détection
        $parsed = $this->parseRfidDetection($rfidString);

        if (!$parsed) {
            return null;
        }

        // Chercher le participant avec ce tag RFID
        $entrant = Entrant::where('rfid_tag', $parsed['tag'])->first();

        if (!$entrant) {
            Log::warning("Aucun participant trouvé pour le tag RFID", [
                'tag' => $parsed['tag'],
                'rfid_string' => $rfidString
            ]);
            return null;
        }

        // Vérifier que le timing point existe
        $timingPoint = TimingPoint::find($timingPointId);

        if (!$timingPoint) {
            Log::error("Timing point introuvable", ['timing_point_id' => $timingPointId]);
            return null;
        }

        // Vérifier que le participant est bien inscrit à cette course
        if ($entrant->race_id !== $timingPoint->race_id) {
            Log::warning("Le participant n'est pas inscrit à cette course", [
                'entrant_id' => $entrant->id,
                'entrant_race_id' => $entrant->race_id,
                'timing_point_race_id' => $timingPoint->race_id
            ]);
            return null;
        }

        // Éviter les doublons (même participant, même point, dans les 2 secondes)
        $existingDetection = RaceTime::where('entrant_id', $entrant->id)
            ->where('timing_point_id', $timingPointId)
            ->where('detection_time', '>=', $parsed['timestamp']->copy()->subSeconds(2))
            ->where('detection_time', '<=', $parsed['timestamp']->copy()->addSeconds(2))
            ->first();

        if ($existingDetection) {
            Log::info("Détection dupliquée ignorée", [
                'entrant_id' => $entrant->id,
                'timing_point_id' => $timingPointId,
                'timestamp' => $parsed['timestamp']->toDateTimeString()
            ]);
            return $existingDetection;
        }

        // Créer l'enregistrement
        $raceTime = RaceTime::create([
            'entrant_id' => $entrant->id,
            'timing_point_id' => $timingPointId,
            'detection_time' => $parsed['timestamp'],
            'detection_type' => 'rfid',
            'rfid_tag_read' => $parsed['tag']
        ]);

        // Broadcaster l'événement pour mise à jour temps réel
        event(new RaceTimeRecorded($raceTime));

        Log::info("Détection RFID enregistrée", [
            'race_time_id' => $raceTime->id,
            'entrant_id' => $entrant->id,
            'entrant_name' => "{$entrant->firstname} {$entrant->lastname}",
            'bib_number' => $entrant->bib_number,
            'timing_point' => $timingPoint->name,
            'detection_time' => $parsed['timestamp']->toDateTimeString()
        ]);

        return $raceTime;
    }

    /**
     * Traiter un batch de détections RFID
     *
     * @param array $detections Tableau de détections RFID
     * @param int $timingPointId ID du point de chronométrage
     * @return array ['success' => int, 'errors' => int, 'duplicates' => int]
     */
    public function recordBatch(array $detections, int $timingPointId): array
    {
        $stats = [
            'success' => 0,
            'errors' => 0,
            'duplicates' => 0,
            'details' => []
        ];

        foreach ($detections as $rfidString) {
            $result = $this->recordDetection($rfidString, $timingPointId);

            if ($result) {
                // Vérifier si c'est un doublon (created_at très récent)
                if ($result->created_at->diffInSeconds(now()) < 1) {
                    $stats['success']++;
                } else {
                    $stats['duplicates']++;
                }
            } else {
                $stats['errors']++;
                $stats['details'][] = "Erreur: {$rfidString}";
            }
        }

        return $stats;
    }

    /**
     * Récupérer les dernières détections pour un point de chronométrage
     *
     * @param int $timingPointId ID du point
     * @param int $limit Nombre de résultats
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentDetections(int $timingPointId, int $limit = 50)
    {
        return RaceTime::with(['entrant', 'timingPoint'])
            ->where('timing_point_id', $timingPointId)
            ->orderBy('detection_time', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir les statistiques de détection pour une course
     *
     * @param int $raceId ID de la course
     * @return array
     */
    public function getRaceDetectionStats(int $raceId): array
    {
        $timingPoints = TimingPoint::where('race_id', $raceId)
            ->with(['raceTimes' => function ($query) {
                $query->select('timing_point_id')
                    ->selectRaw('COUNT(*) as total_detections')
                    ->selectRaw('COUNT(DISTINCT entrant_id) as unique_participants')
                    ->groupBy('timing_point_id');
            }])
            ->get();

        $stats = [];
        foreach ($timingPoints as $point) {
            $stats[$point->name] = [
                'point_type' => $point->point_type,
                'distance_km' => $point->distance_km,
                'total_detections' => $point->raceTimes->sum('total_detections') ?? 0,
                'unique_participants' => $point->raceTimes->sum('unique_participants') ?? 0
            ];
        }

        return $stats;
    }

    /**
     * Simuler des détections RFID pour tests
     *
     * @param int $raceId ID de la course
     * @param int $timingPointId ID du point
     * @param int $count Nombre de détections à simuler
     * @return array Statistiques
     */
    public function simulateDetections(int $raceId, int $timingPointId, int $count = 10): array
    {
        $entrants = Entrant::where('race_id', $raceId)
            ->inRandomOrder()
            ->limit($count)
            ->get();

        $stats = ['success' => 0, 'errors' => 0];
        $baseTime = now();

        foreach ($entrants as $index => $entrant) {
            // Générer un timestamp avec délai progressif (1 seconde entre chaque)
            $timestamp = $baseTime->copy()->addSeconds($index);

            // Format SportLab: [TAG]:aYYYYMMDDHHMMSSmmm
            $rfidString = sprintf(
                "[%s]:a%s%03d",
                $entrant->rfid_tag,
                $timestamp->format('YmdHis'),
                rand(0, 999) // millisecondes aléatoires
            );

            $result = $this->recordDetection($rfidString, $timingPointId);

            if ($result) {
                $stats['success']++;
            } else {
                $stats['errors']++;
            }
        }

        return $stats;
    }
}
