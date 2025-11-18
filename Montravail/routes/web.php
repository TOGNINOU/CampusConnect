<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


// Page d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Tableau de bord après connexion
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes pour le profil utilisateur
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ===============================
// ROUTES RESERVATIONS
// ===============================
Route::middleware('auth')->group(function () {

    // Tous les utilisateurs connectés peuvent voir les réservations
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');

    // ----- ENSEIGNANTS -----
    Route::get('/debug-role', function () {
        return Auth::user()->role ?? 'Aucun utilisateur connecté';
    
    })->middleware('auth');
    Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
        Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
        Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
        Route::patch('/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');


    // Route::middleware('role:teacher')->group(function () {
        
    // });

    // ----- ADMIN -----
    Route::middleware(['auth'])->group(function () {
    Route::post('/reservations/{reservation}/validate', [ReservationController::class, 'validateReservation'])->name('reservations.validate');
        Route::post('/reservations/{reservation}/reject', [ReservationController::class, 'rejectReservation'])->name('reservations.reject');
        Route::patch('/reservations/{reservation}/admin-update', [ReservationController::class, 'update'])->name('reservations.admin-update');

        // Gestion utilisateurs (CRUD complet sauf show)
        Route::resource('users', UserController::class)->except(['show']);
    });

});

require __DIR__.'/auth.php';
