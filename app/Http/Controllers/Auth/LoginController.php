<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        $user = Socialite::driver('google')->user();

        return $this->handleSocialAuthentication($request, [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
        ]);
    }

    /**
     * @param  Request  $request
     * @param  array  $info
     * @return Response
     */
    protected function handleSocialAuthentication(Request $request, array $info)
    {
        $user = User::where('email', $info['email'])->first();

        if (!$user) {

            $user = User::create([
                'email' => $info['email'],
                'name' => $info['name'],
                'password' => Str::random(12),
                'password_set' => false,
            ]);
        }

        Auth::login($user);

        return $this->sendLoginResponse($request);
    }
}
