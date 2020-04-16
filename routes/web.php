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

use Illuminate\Http\Request;
use Illuminate\Support\Str;

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


Route::get('/redirect', function (Request $request) {
    $request->session()->put('state', $state = Str::random(40));

    $request->session()->put('code_verifier', $code_verifier = Str::random(128));

    $codeChallenge = strtr(rtrim(
        base64_encode(hash('sha256', $code_verifier, true))
        , '='), '+/', '-_');

    $query = http_build_query([
        'client_id' => '4',
        'redirect_uri' => 'https://fvm.veem.online/callback',
        'response_type' => 'code',
        'scope' => '',
        'state' => $state,
        'code_challenge' => $codeChallenge,
        'code_challenge_method' => 'S256',
    ]);

    return redirect('https://fvm.veem.online/oauth/authorize?'.$query);
});

Route::get('/callback', function (Request $request) {
    $state = $request->session()->pull('state');

    $codeVerifier = $request->session()->pull('code_verifier');

    throw_unless(
        strlen($state) > 0 && $state === $request->state,
        InvalidArgumentException::class
    );

    $http = new GuzzleHttp\Client();
    $response = $http->post('https://fvm.veem.online/oauth/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => '1',
            'client_secret' => 'lbk9leQq8cgUafDzFNvyyWQMfeSb742uK4IZfosq',
            'redirect_uri' => 'https://fvm.veem.online/login_success',
            'code_verifier' => $codeVerifier,
            'code' => $request->code,
        ],
    ]);

    return json_decode((string) $response->getBody(), true);
});

Route::get('/login_success', function(Request $request) {
    return response()->json($request->all());
});
