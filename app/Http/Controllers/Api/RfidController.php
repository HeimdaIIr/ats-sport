<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ChronoFront\RfidService;
use App\Models\ChronoFront\TimingPoint;
use App\Models\ChronoFront\RaceTime;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

/**
 * Controller pour gérer les détections RFID du lecteur SportLab 2.0
 */
class RfidController extends Controller
{
    protected RfidService $rfidService;

    public function __construct(RfidService $rfidService)
    {
        $this->rfidService = $rfidService;
    }

    /**
     * Enregistrer une détection RFID unique
     *
     * POST /api/rfid/detection
     * Body: {
     *   "rfid": "[2000001]:a20250316143025123",
     *   "timing_point_id": 1
     * }
     */
    public function recordDetection(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'rfid' => 'required|string',
            'timing_point_id' => 'required|integer|exists:timing_points,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        $raceTime = $this->rfidService->recordDetection(
            $request->input('rfid'),
            $request->input('timing_point_id')
        );

        if (!$raceTime) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible d\'enregistrer la détection (tag inconnu ou format invalide)'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Détection enregistrée avec succès',
            'data' => [
                'race_time_id' => $raceTime->id,
                'entrant' => [
                    'id' => $raceTime->entrant->id,
                    'bib_number' => $raceTime->entrant->bib_number,
                    'name' => "{$raceTime->entrant->firstname} {$raceTime->entrant->lastname}"
                ],
                'timing_point' => $raceTime->timingPoint->name,
                'detection_time' => $raceTime->detection_time->toDateTimeString()
            ]
        ], 201);
    }

    /**
     * Enregistrer un batch de détections RFID
     *
     * POST /api/rfid/batch
     * Body: {
     *   "detections": [
     *     "[2000001]:a20250316143025123",
     *     "[2000002]:a20250316143026456"
     *   ],
     *   "timing_point_id": 1
     * }
     */
    public function recordBatch(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'detections' => 'required|array|min:1|max:1000',
            'detections.*' => 'required|string',
            'timing_point_id' => 'required|integer|exists:timing_points,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        $stats = $this->rfidService->recordBatch(
            $request->input('detections'),
            $request->input('timing_point_id')
        );

        return response()->json([
            'success' => true,
            'message' => "Batch traité : {$stats['success']} succès, {$stats['errors']} erreurs, {$stats['duplicates']} doublons",
            'stats' => $stats
        ], 200);
    }

    /**
     * Stream endpoint pour le lecteur SportLab 2.0 (Raspberry Pi)
     * Reçoit les détections RFID en continu
     *
     * POST /api/rfid/stream/{timingPointId}
     * Body (ligne par ligne): [TAG]:aYYYYMMDDHHMMSSmmm
     */
    public function stream(Request $request, int $timingPointId): JsonResponse
    {
        // Vérifier que le timing point existe
        $timingPoint = TimingPoint::find($timingPointId);

        if (!$timingPoint) {
            return response()->json([
                'success' => false,
                'message' => 'Timing point introuvable'
            ], 404);
        }

        // Parser le body (une détection par ligne)
        $body = $request->getContent();
        $detections = array_filter(
            array_map('trim', explode("\n", $body)),
            fn($line) => !empty($line)
        );

        if (empty($detections)) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune détection dans le stream'
            ], 400);
        }

        $stats = $this->rfidService->recordBatch($detections, $timingPointId);

        return response()->json([
            'success' => true,
            'message' => "Stream traité : {$stats['success']} détections enregistrées",
            'stats' => $stats,
            'timing_point' => $timingPoint->name
        ], 200);
    }

    /**
     * Récupérer les dernières détections pour un point de chronométrage
     *
     * GET /api/rfid/timing-point/{timingPointId}/recent?limit=50
     */
    public function recentDetections(int $timingPointId, Request $request): JsonResponse
    {
        $limit = $request->input('limit', 50);
        $limit = min($limit, 500); // Max 500

        $detections = $this->rfidService->getRecentDetections($timingPointId, $limit);

        return response()->json([
            'success' => true,
            'data' => $detections->map(function ($raceTime) {
                return [
                    'id' => $raceTime->id,
                    'bib_number' => $raceTime->entrant->bib_number,
                    'name' => "{$raceTime->entrant->firstname} {$raceTime->entrant->lastname}",
                    'gender' => $raceTime->entrant->gender,
                    'detection_time' => $raceTime->detection_time->toDateTimeString(),
                    'detection_type' => $raceTime->detection_type,
                    'timing_point' => $raceTime->timingPoint->name,
                ];
            }),
            'count' => $detections->count()
        ]);
    }

    /**
     * Obtenir les statistiques de détection pour une course
     *
     * GET /api/rfid/race/{raceId}/stats
     */
    public function raceStats(int $raceId): JsonResponse
    {
        $stats = $this->rfidService->getRaceDetectionStats($raceId);

        return response()->json([
            'success' => true,
            'race_id' => $raceId,
            'stats' => $stats
        ]);
    }

    /**
     * Simuler des détections RFID pour tests (DEVELOPMENT ONLY)
     *
     * POST /api/rfid/simulate
     * Body: {
     *   "race_id": 1,
     *   "timing_point_id": 1,
     *   "count": 10
     * }
     */
    public function simulate(Request $request): JsonResponse
    {
        // Désactiver en production
        if (app()->environment('production')) {
            return response()->json([
                'success' => false,
                'message' => 'Simulation désactivée en production'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'race_id' => 'required|integer|exists:races,id',
            'timing_point_id' => 'required|integer|exists:timing_points,id',
            'count' => 'integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $stats = $this->rfidService->simulateDetections(
            $request->input('race_id'),
            $request->input('timing_point_id'),
            $request->input('count', 10)
        );

        return response()->json([
            'success' => true,
            'message' => "Simulation terminée : {$stats['success']} détections créées",
            'stats' => $stats
        ]);
    }

    /**
     * Tester le parsing d'une chaîne RFID (utile pour debug)
     *
     * POST /api/rfid/parse
     * Body: { "rfid": "[2000001]:a20250316143025123" }
     */
    public function parseTest(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'rfid' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $parsed = $this->rfidService->parseRfidDetection($request->input('rfid'));

        if (!$parsed) {
            return response()->json([
                'success' => false,
                'message' => 'Format RFID invalide',
                'expected_format' => '[TAG]:aYYYYMMDDHHMMSSmmm',
                'example' => '[2000001]:a20250316143025123'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'parsed' => [
                'tag' => $parsed['tag'],
                'timestamp' => $parsed['timestamp']->toDateTimeString(),
                'timestamp_iso' => $parsed['timestamp']->toIso8601String(),
                'raw' => $parsed['raw']
            ]
        ]);
    }
}
