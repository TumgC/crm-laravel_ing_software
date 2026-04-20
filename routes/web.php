<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OpportunityController;
use App\Http\Controllers\OpportunityProposalController;

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

    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::get('/tickets/search-customers', [TicketController::class, 'searchCustomers'])->name('tickets.search-customers');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}/assign', [TicketController::class, 'assignForm'])->name('tickets.assignForm');
    Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assignStore'])->name('tickets.assignStore');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::patch('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
    Route::post('/tickets/{ticket}/addInteraction', [TicketController::class, 'addInteraction'])->name('tickets.addInteraction');

    Route::get('/customers/create', function () {
        return redirect()->back()->with('success', 'Pendiente crear formulario de cliente.');
    })->name('customers.create');

    Route::get('/opportunities', [OpportunityController::class, 'index'])->name('opportunities.index');
    Route::get('/opportunities/create', [OpportunityController::class, 'create'])->name('opportunities.create');
    Route::post('/opportunities', [OpportunityController::class, 'store'])->name('opportunities.store');
    Route::get('/opportunities/search-customers', [OpportunityController::class, 'searchCustomers'])->name('opportunities.search-customers');
    Route::get('/opportunities/{opportunity}/stage', [OpportunityController::class, 'stageForm'])->name('opportunities.stageForm');
    Route::post('/opportunities/{opportunity}/stage', [OpportunityController::class, 'stageUpdate'])->name('opportunities.stageUpdate');
    Route::get('/opportunities/{opportunity}/history', [OpportunityController::class, 'history'])->name('opportunities.history');
    Route::get('/opportunities/{opportunity}', [OpportunityController::class, 'show'])->name('opportunities.show');
    Route::post('/opportunities/{opportunity}/proposals', [OpportunityProposalController::class, 'store'])->name('proposals.store');
    Route::post('/proposals/{proposal}/status', [OpportunityProposalController::class, 'updateStatus'])->name('proposals.status');
});

require __DIR__ . '/auth.php';