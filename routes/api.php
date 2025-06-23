<?php

use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::post('/tickets', [TicketController::class, 'store']);
Route::get('/tickets', [TicketController::class, 'index']);
Route::post('/tickets/{ticket}/classify', [TicketController::class, 'classify']);

Route::patch('/tickets/{ticket}', [TicketController::class, 'update']);
Route::get('/tickets/{ticket}', [TicketController::class, 'show']);