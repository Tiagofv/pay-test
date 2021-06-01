<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'store']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::middleware('auth:api')->group(function (){
    Route::apiResource('wallets', \App\Http\Controllers\WalletController::class);
    Route::apiResource('transfers', \App\Http\Controllers\TransferController::class);
    Route::get('/detail', [\App\Http\Controllers\AuthController::class, 'show']);
});


