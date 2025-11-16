<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\RaceController;
use App\Http\Controllers\Api\WaveController;
use App\Http\Controllers\Api\EntrantController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ResultController;
use App\Http\Controllers\Api\ImportController;
use App\Http\Controllers\Api\RfidController;
use App\Http\Controllers\Api\ManualTimingController;
use App\Http\Controllers\Api\TimingPointController;

/*
|--------------------------------------------------------------------------
| API Routes - ChronoFront Laravel
|--------------------------------------------------------------------------
|
| API REST pour l'application de chronométrage sportif
|
*/

// Events Routes
Route::apiResource('events', EventController::class);

// Races Routes
Route::apiResource('races', RaceController::class);
Route::get('races/event/{eventId}', [RaceController::class, 'byEvent']);
Route::post('races/{race}/start', [RaceController::class, 'start']);
Route::post('races/{race}/end', [RaceController::class, 'end']);

// Waves Routes
Route::apiResource('waves', WaveController::class);
Route::get('waves/race/{raceId}', [WaveController::class, 'byRace']);
Route::post('waves/{wave}/start', [WaveController::class, 'start']);
Route::post('waves/{wave}/end', [WaveController::class, 'end']);

// Categories Routes
Route::apiResource('categories', CategoryController::class);
Route::post('categories/init-ffa', [CategoryController::class, 'initFFA']);

// Timing Points Routes
Route::apiResource('timing-points', TimingPointController::class);
Route::get('timing-points/race/{raceId}', [TimingPointController::class, 'byRace']);

// Entrants Routes
Route::apiResource('entrants', EntrantController::class);
Route::get('entrants/search', [EntrantController::class, 'search']);
Route::post('entrants/import', [EntrantController::class, 'import']);

// Import CSV Routes (SportLab format)
Route::post('events/{event}/import-csv', [ImportController::class, 'importCsv']);
Route::post('import/validate-csv', [ImportController::class, 'validateCsv']);
Route::get('import/download-template', [ImportController::class, 'downloadTemplate']);

// RFID Routes (SportLab 2.0 Integration)
Route::post('rfid/detection', [RfidController::class, 'recordDetection']);
Route::post('rfid/batch', [RfidController::class, 'recordBatch']);
Route::post('rfid/stream/{timingPointId}', [RfidController::class, 'stream']);
Route::get('rfid/timing-point/{timingPointId}/recent', [RfidController::class, 'recentDetections']);
Route::get('rfid/race/{raceId}/stats', [RfidController::class, 'raceStats']);
Route::post('rfid/parse', [RfidController::class, 'parseTest']);
Route::post('rfid/simulate', [RfidController::class, 'simulate']); // Dev only

// Manual Timing Routes (Backup when RFID fails)
Route::post('manual-timing/record', [ManualTimingController::class, 'recordByBibNumber']);
Route::post('manual-timing/batch', [ManualTimingController::class, 'recordBatch']);
Route::get('manual-timing/timing-point/{timingPointId}/recent', [ManualTimingController::class, 'recentDetections']);
Route::delete('manual-timing/detection/{detectionId}', [ManualTimingController::class, 'deleteDetection']);
Route::get('manual-timing/lookup/bib/{bibNumber}/race/{raceId}', [ManualTimingController::class, 'lookupByBib']);

// Results/Timing Routes
Route::get('results/race/{raceId}', [ResultController::class, 'byRace']);
Route::post('results/time', [ResultController::class, 'addTime']);
Route::post('results/race/{raceId}/recalculate', [ResultController::class, 'recalculatePositions']);
Route::get('results/race/{raceId}/export', [ResultController::class, 'export']);
Route::put('results/{result}', [ResultController::class, 'update']);
Route::delete('results/{result}', [ResultController::class, 'destroy']);

// Results ChronoFront (RFID-based calculation)
Route::post('results/race/{raceId}/calculate', [ResultController::class, 'calculateResults']);
Route::get('results/race/{raceId}/scratch', [ResultController::class, 'scratchRanking']);
Route::get('results/race/{raceId}/gender/{gender}', [ResultController::class, 'genderRanking']);
Route::get('results/race/{raceId}/category/{categoryId}', [ResultController::class, 'categoryRanking']);
Route::get('results/race/{raceId}/statistics', [ResultController::class, 'statistics']);

// Health check
Route::get('health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'app' => 'ChronoFront Laravel'
    ]);
});
