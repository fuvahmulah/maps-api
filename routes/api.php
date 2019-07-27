<?php

use Illuminate\Http\Request;

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

Route::group(['middleware' => 'auth:api'], function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // fetch geojson of all markers
    Route::get('/markers/geojson', 'MarkersController@geoJson');
    // fetch neighbors for a given coordinates
    Route::get('/markers/neighbors', 'MarkersController@neighbors');

});

Route::post('login', 'Api\AuthController@login');
Route::get('/locations', 'MarkersController@locations');
