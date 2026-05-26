<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OpportunityApiController;
use App\Http\Controllers\Api\TicketApiController;

Route::get('/opportunities', [OpportunityApiController::class, 'index']);
Route::get('/tickets', [TicketApiController::class, 'index']);
