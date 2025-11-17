<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChronoFront\Race;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RaceController extends Controller
{
    /**
     * Display a listing of races
     */
    public function index(): JsonResponse
    {
        $races = Race::with(['event', 'waves'])->get();
        return response()->json($races);
    }

    /**
     * Get races for a specific event
     */
    public function byEvent(int $eventId): JsonResponse
    {
        $races = Race::where('event_id', $eventId)
            ->with('waves')
            ->get();

        return response()->json($races);
    }

    /**
     * Store a newly created race
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:200',
            'type' => 'required|in:1_passage,n_laps,infinite_loop',
            'distance' => 'required|numeric|min:0',
            'laps' => 'integer|min:1',
            'best_time' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $race = Race::create($validated);

        return response()->json($race, 201);
    }

    /**
     * Display the specified race
     */
    public function show(Race $race): JsonResponse
    {
        $race->load(['event', 'waves.entrants', 'results.entrant']);
        return response()->json($race);
    }

    /**
     * Update the specified race
     */
    public function update(Request $request, Race $race): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:200',
            'type' => 'sometimes|in:1_passage,n_laps,infinite_loop',
            'distance' => 'sometimes|numeric|min:0',
            'laps' => 'integer|min:1',
            'best_time' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $race->update($validated);

        return response()->json($race);
    }

    /**
     * Remove the specified race
     */
    public function destroy(Race $race): JsonResponse
    {
        $race->delete();
        return response()->json(['message' => 'Race deleted successfully']);
    }

    /**
     * Start a race (TOP départ)
     * Permet de définir ou modifier l'heure de départ
     */
    public function start(Request $request, Race $race): JsonResponse
    {
        $validated = $request->validate([
            'start_time' => 'nullable|date',
        ]);

        // Si pas d'heure fournie, utiliser l'heure actuelle
        $startTime = $validated['start_time'] ?? now();

        $race->update([
            'start_time' => $startTime
        ]);

        return response()->json([
            'message' => $race->wasChanged('start_time')
                ? 'TOP départ enregistré avec succès'
                : 'Heure de départ mise à jour',
            'race' => $race->fresh()
        ]);
    }

    /**
     * End a race (TOP arrivée)
     */
    public function end(Request $request, Race $race): JsonResponse
    {
        if (!$race->start_time) {
            return response()->json([
                'message' => 'La course n\'a pas encore été démarrée (TOP départ manquant)'
            ], 400);
        }

        $validated = $request->validate([
            'end_time' => 'nullable|date',
        ]);

        // Si pas d'heure fournie, utiliser l'heure actuelle
        $endTime = $validated['end_time'] ?? now();

        $race->update([
            'end_time' => $endTime
        ]);

        return response()->json([
            'message' => 'TOP arrivée enregistré avec succès',
            'race' => $race->fresh()
        ]);
    }
}
