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

Route::get('/', 'App\\Http\\Controllers\\IndexController@index')->name('/');
Route::get('/login', 'App\\Http\\Controllers\\AdminController@login')->name('login');
Route::post('/login', 'App\\Http\\Controllers\\AdminController@loginAdmin')->name('login');


Route::get('/admin', 'App\\Http\\Controllers\\AdminController@index')->name('admin');
Route::post('/updateprize', 'App\\Http\\Controllers\\AdminController@updatePrize')->name('updateprize');
Route::post('/updatewinner', 'App\\Http\\Controllers\\AdminController@updateWinner')->name('updatewinner');
Route::get('/getconfigwinner', 'App\\Http\\Controllers\\AdminController@getConfigWinner')->name('getconfigwinner');
