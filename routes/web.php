<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;
    use App\Http\Controllers\OpportunityController;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/tickets/create', [TicketController::class, 'create'])
    ->middleware(['auth'])
    ->name('tickets.create');

Route::post('/tickets', [TicketController::class, 'store'])
    ->middleware(['auth'])
    ->name('tickets.store');

Route::get('/tickets', [TicketController::class, 'index'])
    ->middleware(['auth'])
    ->name('tickets.index');

Route::get('/tickets/{ticket}/assign', [TicketController::class, 'assignForm'])
    ->middleware(['auth'])
    ->name('tickets.assignForm');

Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assignStore'])
    ->middleware(['auth'])
    ->name('tickets.assignStore');

Route::get('/tickets/{ticket}', [TicketController::class, 'show'])
    ->name('tickets.show');

Route::patch('/tickets/{ticket}', [TicketController::class, 'update'])
    ->middleware(['auth'])
    ->name('tickets.update');

Route::get('/opportunities', [OpportunityController::class, 'index'])
    ->middleware(['auth'])
    ->name('opportunities.index');

Route::get('/opportunities/create', [OpportunityController::class, 'create'])
    ->middleware(['auth'])
    ->name('opportunities.create');

Route::post('/opportunities', [OpportunityController::class, 'store'])
    ->middleware(['auth'])
    ->name('opportunities.store');

// H2: cambio de etapa
Route::get('/opportunities/{opportunity}/stage', [OpportunityController::class, 'stageForm'])
    ->middleware(['auth'])
    ->name('opportunities.stageForm');

Route::post('/opportunities/{opportunity}/stage', [OpportunityController::class, 'stageUpdate'])
    ->middleware(['auth'])
    ->name('opportunities.stageUpdate');
