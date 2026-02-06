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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/player', [PlayerController::class, 'index'])->name('player.index');
Route::get('/player/search', [PlayerController::class, 'show'])->name('player.search');
