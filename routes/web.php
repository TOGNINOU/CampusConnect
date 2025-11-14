<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    // If user is authenticated, send them to a useful default (reservations list)
    if (Auth::check()) {
        return redirect()->route('reservations.index');
    }

    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Module 2 - Reservations
    Route::resource('rooms', App\Http\Controllers\RoomController::class);
    Route::resource('equipments', App\Http\Controllers\EquipmentController::class);
    // Calendar and events for availability
    Route::get('calendar', [App\Http\Controllers\CalendarController::class, 'index'])->name('calendar.index');
    // Declare the events route before the reservations resource so "reservations/{reservation}"
    // does not greedily capture the "events" segment and cause a 404 via model binding.
    Route::get('reservations/events', [App\Http\Controllers\ReservationController::class, 'events'])->name('reservations.events');
    Route::resource('reservations', App\Http\Controllers\ReservationController::class)->except(['edit']);
    
    // Module 3 - Projects (full web resource: index, create, store, show, edit, update, destroy)
    Route::resource('projects', App\Http\Controllers\ProjectController::class);
    Route::get('projects/{project}/members', [App\Http\Controllers\ProjectMemberController::class, 'index'])->name('projects.members.index');
    Route::post('projects/{project}/members', [App\Http\Controllers\ProjectMemberController::class, 'store'])->name('projects.members.store');
    Route::delete('projects/{project}/members/{user}', [App\Http\Controllers\ProjectMemberController::class, 'destroy'])->name('projects.members.destroy');

    Route::get('projects/{project}/deliverables', [App\Http\Controllers\ProjectDeliverableController::class, 'index'])->name('projects.deliverables.index');
    Route::post('projects/{project}/deliverables', [App\Http\Controllers\ProjectDeliverableController::class, 'store'])->name('projects.deliverables.store');
    Route::get('projects/{project}/deliverables/{deliverable}', [App\Http\Controllers\ProjectDeliverableController::class, 'download'])->name('projects.deliverables.download');
    Route::delete('projects/{project}/deliverables/{deliverable}', [App\Http\Controllers\ProjectDeliverableController::class, 'destroy'])->name('projects.deliverables.destroy');
});

require __DIR__.'/auth.php';
