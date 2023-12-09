<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PlayerController;
use App\Http\Controllers\API\GameController;
use App\Http\Controllers\Game2Controller;

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


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('game')->group(function (){
    Route::post('iniciar', [Game2Controller::class, 'startGame']);
    Route::post('entrar', [Game2Controller::class, 'enterGame']);
    Route::get('usuarios', [Game2Controller::class, 'listUsers']);
    Route::delete('cache', [Game2Controller::class, 'clearCache']);
    Route::get('generar', [Game2Controller::class, 'generateRandomWord']);
});

