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

    // returns paginated list of all tags
    Route::get('/tags', 'TagsController@index');

    // returns matched tags based on a like search on the english translation of the tag name
    Route::get('/tags/{keyword}', 'TagsController@search');

    Route::post('/markers', 'MarkersController@store');

    // fetch geojson of all markers
    Route::get('/markers/geojson', 'MarkersController@geoJson');
    // fetch neighbors for a given coordinates
    // Route::get('/markers/neighbors', 'MarkersController@neighbors');
    Route::get('/locations', 'MarkersController@locations');
    Route::get('/markers/neighbors', 'MarkersController@neighbors');
    Route::get('/marker-types', 'MarkersTypeController@index');
});

Route::post('login', 'Api\AuthController@login');
Route::get('/locations', 'MarkersController@locations');
Route::get('/markers/neighbors', 'MarkersController@neighbors');

Route::get('/marker-types', 'MarkersTypeController@index');
