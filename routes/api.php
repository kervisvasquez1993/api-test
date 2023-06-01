<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\TarjetaController;
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
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name("register");

// Rutas para usuarios

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me'])->name("me");
// Rutas para clientes
    Route::get("clientes", [ClienteController::class, "index"]);
    Route::get("clientes/{cliente}", [ClienteController::class, "show"]);
    Route::put("clientes/{cliente}", [ClienteController::class, "update"]);
    Route::get("clientes/{id}/cambiar-estado", [ClienteController::class, "cambiarEstado"]);
    Route::delete("clientes/{cliente}", [ClienteController::class, "destroy"]);
    Route::post("clientes", [ClienteController::class, "store"]);
// Rutas para tarjetas
    Route::get('tarjetas', [TarjetaController::class, 'index']);
    Route::get('tarjetas/{tarjeta}', [TarjetaController::class, 'show']);
    Route::post('tarjetas', [TarjetaController::class, 'store']);
    Route::post('tarjetas/{tarjeta}/cliente', [TarjetaController::class, 'tarjetaCliente']);
    Route::post('cliente/{cliente}/tarjeta', [TarjetaController::class, 'clienteTarjeta']);
    Route::put('tarjetas/{id}', [TarjetaController::class, 'update']);
    Route::delete('tarjetas/{tarjeta}', [TarjetaController::class, 'destroy']);
    Route::get('generartxt', [TarjetaController::class, 'descargarInformacionTxt']);

// Rutas para la relación entre cliente y tarjeta
    // Route::get('clientes/{cliente}/tarjetas', 'ClienteTarjetaController@index');
    // Route::post('clientes/{cliente}/tarjetas/{tarjeta}', 'ClienteTarjetaController@attach');
    // Route::delete('clientes/{cliente}/tarjetas/{tarjeta}', 'ClienteTarjetaController@detach');

});
