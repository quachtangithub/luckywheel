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


Route::get('/login', 'App\\Http\\Controllers\\AdminController@login')->name('login');
Route::post('/login', 'App\\Http\\Controllers\\AdminController@loginAdmin')->name('login');

Route::group(['middleware' => ['CheckLogin']], function () {
    Route::get('/', 'App\\Http\\Controllers\\IndexController@index')->name('/');
    Route::get('/framecontainer', 'App\\Http\\Controllers\\IndexController@frameContainer')->name('framecontainer');
    Route::get('/admin', 'App\\Http\\Controllers\\AdminController@index')->name('admin');
    Route::get('/user', 'App\\Http\\Controllers\\AdminController@user')->name('user');
    Route::get('/user/delete/{id}', 'App\\Http\\Controllers\\AdminController@deleteUser')->name('user/delete');
    Route::post('/user', 'App\\Http\\Controllers\\AdminController@updateUser')->name('user');
    Route::post('/updateprize', 'App\\Http\\Controllers\\AdminController@updatePrize')->name('updateprize');
    Route::post('/updatewinner', 'App\\Http\\Controllers\\AdminController@updateWinner')->name('updatewinner');
    Route::get('/getconfigwinner', 'App\\Http\\Controllers\\AdminController@getConfigWinner')->name('getconfigwinner');

    Route::get('/prize/{id}', 'App\\Http\\Controllers\\AdminController@getPrize')->name('prize');
    Route::get('/prize/delete/{id}', 'App\\Http\\Controllers\\AdminController@deletePrize')->name('prize/delete');

    Route::get('/testnotification/{id}', 'App\\Http\\Controllers\\AdminController@testnotification')->name('testnotification');
    Route::get('/play/{id}', 'App\\Http\\Controllers\\AdminController@playPrizeInControl')->name('play');
    Route::get('/returnprize', 'App\\Http\\Controllers\\AdminController@returnPrize')->name('returnprize');
    Route::post('/updateprizeincontrol', 'App\\Http\\Controllers\\AdminController@updatePrizeInControl')->name('updateprizeincontrol');

    Route::post('/secretvalue', 'App\\Http\\Controllers\\AdminController@updateSecretValue')->name('secretvalue');

    Route::get('/logout', 'App\\Http\\Controllers\\AdminController@logout')->name('logout');
});