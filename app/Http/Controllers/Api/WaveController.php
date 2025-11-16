<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChronoFront\Wave;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WaveController extends Controller
{
    /**
     * Display a listing of waves
     */
    public function index(): JsonResponse
    {
        $waves = Wave::with(['race', 'entrants'])->get();
        return response()->json($waves);
    }

    /**
     * Get waves for a specific race
     */
    public function byRace(int $raceId): JsonResponse
    {
        $waves = Wave::where('race_id', $raceId)
            ->with('entrants')
            ->get();

        return response()->json($waves);
    }

    /**
     * Store a newly created wave
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'race_id' => 'required|exists:races,id',
            'name' => 'required|string|max:100',
            'max_capacity' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'start_time' => 'nullable|date_format:H:i',
        ]);

        $wave = Wave::create($validated);

        return response()->json($wave, 201);
    }

    /**
     * Display the specified wave
     */
    public function show(Wave $wave): JsonResponse
    {
        $wave->load(['race', 'entrants.category']);
        return response()->json($wave);
    }

    /**
     * Update the specified wave
     */
    public function update(Request $request, Wave $wave): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'max_capacity' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'start_time' => 'nullable|date_format:H:i',
        ]);

        $wave->update($validated);

        return response()->json($wave);
    }

    /**
     * Remove the specified wave
     */
    public function destroy(Wave $wave): JsonResponse
    {
        $wave->delete();
        return response()->json(['message' => 'Wave deleted successfully']);
    }

    /**
     * Start a wave
     */
    public function start(Wave $wave): JsonResponse
    {
        if ($wave->is_started) {
            return response()->json([
                'message' => 'Wave already started'
            ], 400);
        }

        $wave->update([
            'start_time' => now(),
            'is_started' => true
        ]);

        return response()->json([
            'message' => 'Wave started successfully',
            'wave' => $wave
        ]);
    }

    /**
     * End a wave
     */
    public function end(Wave $wave): JsonResponse
    {
        if (!$wave->is_started) {
            return response()->json([
                'message' => 'Wave has not started yet'
            ], 400);
        }

        if ($wave->end_time) {
            return response()->json([
                'message' => 'Wave already ended'
            ], 400);
        }

        $wave->update([
            'end_time' => now()
        ]);

        return response()->json([
            'message' => 'Wave ended successfully',
            'wave' => $wave
        ]);
    }
}
