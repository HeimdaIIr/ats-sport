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
Route::middleware(['auth'])->group(function () {
    Route::get('/organisateur', [OrganizatorController::class, 'index'])->name('organizer.dashboard');
    Route::get('/organisateur/creer', [OrganizatorController::class, 'create'])->name('organizer.create');
    Route::post('/organisateur/creer', [OrganizatorController::class, 'store'])->name('organizer.store');
    
    // Routes pour édition et gestion de la vedette (à réorganiser c'est caca la)
    Route::get('/organisateur/modifier/{id}', [OrganizatorController::class, 'edit'])->name('organizer.edit');
    Route::put('/organisateur/modifier/{id}', [OrganizatorController::class, 'update'])->name('organizer.update');
    Route::post('/organisateur/vedette/{id}', [OrganizatorController::class, 'toggleFeatured'])->name('organizer.toggle-featured');
});

// Routes ChronoFront - Module de Chronométrage
Route::prefix('chronofront')->name('chronofront.')->group(function () {
    Route::get('/', [ChronoFrontController::class, 'dashboard'])->name('dashboard');
    Route::get('/events', [ChronoFrontController::class, 'events'])->name('events');
    Route::get('/races', [ChronoFrontController::class, 'races'])->name('races');
    Route::get('/entrants', [ChronoFrontController::class, 'entrants'])->name('entrants');
    Route::get('/entrants/import', [ChronoFrontController::class, 'entrantsImport'])->name('entrants.import');
    Route::get('/waves', [ChronoFrontController::class, 'waves'])->name('waves');
    Route::get('/timing', [ChronoFrontController::class, 'timing'])->name('timing');
    Route::get('/results', [ChronoFrontController::class, 'results'])->name('results');
    Route::get('/categories', [ChronoFrontController::class, 'categories'])->name('categories');
});

// Routes plugin authentification Laravel (WIP)
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');