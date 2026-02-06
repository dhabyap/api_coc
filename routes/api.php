<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use App\Http\Controllers\CocController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('coc')->group(function () {
    Route::get('/status', [CocController::class, 'status']);
    Route::get('/player/{playerTag}', [CocController::class, 'player']);
    Route::get('/clan/{clanTag}', [CocController::class, 'clan']);
    Route::get('/clan/{clanTag}/members', [CocController::class, 'clanMembers']);
    Route::get('/war/{clanTag}', [CocController::class, 'war']);
    Route::get('/cwl/{clanTag}', [CocController::class, 'cwl']);
});
