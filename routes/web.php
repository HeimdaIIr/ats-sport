<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrganizatorController;
use App\Http\Controllers\ChronoFrontController;

// Routes onglets general
Route::get('/', [EventController::class, 'index']);
Route::get('/event/{slug}', [EventController::class, 'show'])->name('event.show');
Route::get('/resultats', [EventController::class, 'results'])->name('events.results');

// Routes onglet organisateur
Route::get('/organisateur', [OrganizatorController::class, 'index'])->name('organizer.dashboard');
Route::get('/organisateur/creer', [OrganizatorController::class, 'create'])->name('organizer.create');
Route::post('/organisateur/creer', [OrganizatorController::class, 'store'])->name('organizer.store');

// Routes ChronoFront - Module de Chronométrage
Route::prefix('chronofront')->name('chronofront.')->group(function () {
    Route::get('/', [ChronoFrontController::class, 'dashboard'])->name('dashboard');
    Route::get('/events', [ChronoFrontController::class, 'events'])->name('events.index');
    Route::get('/races', [ChronoFrontController::class, 'races'])->name('races.index');
    Route::get('/entrants', [ChronoFrontController::class, 'entrants'])->name('entrants.index');
    Route::get('/entrants/import', [ChronoFrontController::class, 'entrantsImport'])->name('entrants.import');
    Route::get('/waves', [ChronoFrontController::class, 'waves'])->name('waves.index');
    Route::get('/timing', [ChronoFrontController::class, 'timing'])->name('timing.index');
    Route::get('/manual-timing', [ChronoFrontController::class, 'manualTiming'])->name('manual.timing');
    Route::get('/results', [ChronoFrontController::class, 'results'])->name('results.index');
    Route::get('/categories', [ChronoFrontController::class, 'categories'])->name('categories.index');
});

// Routes plugin authentification Laravel
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');