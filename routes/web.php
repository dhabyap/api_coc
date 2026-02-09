<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Http\Controllers\PlayerController;
use App\Http\Controllers\SuggestionController;

Route::get('/', [PlayerController::class, 'home'])->name('player.home');
Route::get('/player', [PlayerController::class, 'home']); // Redirect or same as home
Route::get('/player/search', [PlayerController::class, 'search'])->name('player.search');
Route::get('/player/{tag}', [PlayerController::class, 'show'])->name('player.show');

Route::post('/suggestions', [SuggestionController::class, 'store'])->name('suggestions.store');

Route::get('/api/events/summary', [PlayerController::class, 'eventsSummary'])->name('events.summary');
