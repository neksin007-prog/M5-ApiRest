<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\AsistenteController;
use App\Http\Controllers\PonenteController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::apiResource('eventos', EventoController::class);
Route::apiResource('asistentes', AsistenteController::class);
Route::apiResource('ponentes', PonenteController::class);