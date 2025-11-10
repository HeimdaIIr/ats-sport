<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrganizatorController;

// Routes onglets general
Route::get('/', [EventController::class, 'index']);
Route::get('/event/{slug}', [EventController::class, 'show'])->name('event.show');
Route::get('/resultats', [EventController::class, 'results'])->name('events.results');

// Routes onglet organisateur
Route::get('/organisateur', [OrganizatorController::class, 'index'])->name('organizer.dashboard');
Route::get('/organisateur/creer', [OrganizatorController::class, 'create'])->name('organizer.create');
Route::post('/organisateur/creer', [OrganizatorController::class, 'store'])->name('organizer.store');

// Routes plugin authentification Laravel
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');