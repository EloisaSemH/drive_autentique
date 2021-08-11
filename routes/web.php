<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', 'App\Http\Controllers\DashboardController@index')->name('index');
Route::post('/etapa-2', 'App\Http\Controllers\DashboardController@step')->name('step');
Route::post('/revisao', 'App\Http\Controllers\DashboardController@review')->name('review');
Route::post('/enviado', 'App\Http\Controllers\DashboardController@send')->name('send');
Route::post('/sucesso', 'App\Http\Controllers\DashboardController@success')->name('success');
//Route::post('/', 'App\Http\Controllers\DashboardController@send')->name('index.send');
Auth::routes();
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
