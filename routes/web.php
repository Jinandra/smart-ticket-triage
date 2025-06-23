<?php

use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', fn() => view('tickets'))->name('tickets');
Route::get('/stats', [TicketController::class, 'stats'])->name('stats');

