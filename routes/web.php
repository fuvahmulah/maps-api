<?php

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

Route::get('/', function () {
    return view('index');
});

Auth::routes();

Route::get('login/google', 'Auth\LoginController@redirectToGoogle');
Route::get('login/google/callback', 'Auth\LoginController@handleGoogleCallback');


Route::group(['middleware' => 'auth'], function() {
    Route::group(['prefix' => 'me'], function () {
        Route::get('set-password', 'UserController@setPassword')->name('me.set-password')->middleware('redirect-password');
        Route::post('set-password', 'UserController@savePassword')->name('me.save-password')->middleware('redirect-password');
    });

    Route::group(['middleware' => 'check-password'], function () {
        Route::get('/home', 'HomeController@index')->name('home');

    });
});
