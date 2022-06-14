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

Route::post('login', 'App\Http\Controllers\Api\AuthController@login');

Route::group(['middleware' => 'auth:api'], function() {

});

Route::get('brosur', 'App\Http\Controllers\Api\BrosurController@index');
Route::get('brosur/{id_mobil}', 'App\Http\Controllers\Api\BrosurController@show');


Route::get('promo', 'App\Http\Controllers\Api\PromoController@index');
Route::get('promo/{id_promo}', 'App\Http\Controllers\Api\PromoController@show');

Route::get('customer/{id}', 'App\Http\Controllers\Api\CustomerController@show');
Route::put('customer/{id}', 'App\Http\Controllers\Api\CustomerController@update');

Route::get('driver/{id}', 'App\Http\Controllers\Api\DriverController@show');
Route::put('driver/{id}', 'App\Http\Controllers\Api\DriverController@update');

Route::get('transaksi/{id}', 'App\Http\Controllers\Api\TransaksiController@index');

Route::get('laporan/{pointer}/{month}/{year}', 'App\Http\Controllers\Api\LaporanController@index');