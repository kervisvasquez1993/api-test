<?php

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

// Rutas para usuarios
Route::get('users', 'UserController@index');
Route::get('users/{id}', 'UserController@show');
Route::post('users', 'UserController@store');
Route::put('users/{id}', 'UserController@update');
Route::delete('users/{id}', 'UserController@destroy');

// Rutas para clientes
Route::get('clientes', 'ClienteController@index');
Route::get('clientes/{id}', 'ClienteController@show');
Route::post('clientes', 'ClienteController@store');
Route::put('clientes/{id}', 'ClienteController@update');
Route::delete('clientes/{id}', 'ClienteController@destroy');

// Rutas para tarjetas
Route::get('tarjetas', 'TarjetaController@index');
Route::get('tarjetas/{id}', 'TarjetaController@show');
Route::post('tarjetas', 'TarjetaController@store');
Route::put('tarjetas/{id}', 'TarjetaController@update');
Route::delete('tarjetas/{id}', 'TarjetaController@destroy');

// Rutas para la relación entre cliente y tarjeta
Route::get('clientes/{cliente}/tarjetas', 'ClienteTarjetaController@index');
Route::post('clientes/{cliente}/tarjetas/{tarjeta}', 'ClienteTarjetaController@attach');
Route::delete('clientes/{cliente}/tarjetas/{tarjeta}', 'ClienteTarjetaController@detach');
