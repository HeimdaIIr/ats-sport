<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChronoFront\TimingPoint;
use App\Models\ChronoFront\Race;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TimingPointController extends Controller
{
    /**
     * List all timing points
     */
    public function index(): JsonResponse
    {
        $timingPoints = TimingPoint::with('race')->orderBy('order_number')->get();
        return response()->json($timingPoints);
    }

    /**
     * Get timing points for a specific race
     */
    public function byRace(int $raceId): JsonResponse
    {
        $timingPoints = TimingPoint::where('race_id', $raceId)
            ->orderBy('order_number')
            ->get();

        return response()->json($timingPoints);
    }

    /**
     * Create a new timing point
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'race_id' => 'required|exists:races,id',
            'name' => 'required|string|max:100',
            'distance_km' => 'required|numeric|min:0',
            'point_type' => 'required|in:start,intermediate,finish',
            'order_number' => 'required|integer|min:1'
        ]);

        $timingPoint = TimingPoint::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Point de chronométrage créé',
            'data' => $timingPoint
        ], 201);
    }

    /**
     * Get a specific timing point
     */
    public function show(int $id): JsonResponse
    {
        $timingPoint = TimingPoint::with('race')->findOrFail($id);
        return response()->json($timingPoint);
    }

    /**
     * Update a timing point
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $timingPoint = TimingPoint::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'distance_km' => 'sometimes|numeric|min:0',
            'point_type' => 'sometimes|in:start,intermediate,finish',
            'order_number' => 'sometimes|integer|min:1'
        ]);

        $timingPoint->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Point de chronométrage mis à jour',
            'data' => $timingPoint
        ]);
    }

    /**
     * Delete a timing point
     */
    public function destroy(int $id): JsonResponse
    {
        $timingPoint = TimingPoint::findOrFail($id);
        $timingPoint->delete();

        return response()->json([
            'success' => true,
            'message' => 'Point de chronométrage supprimé'
        ]);
    }
}
