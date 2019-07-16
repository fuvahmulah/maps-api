<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\Me\SetPasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function setPassword(Request $request)
    {
        $user = $request->user();
        return view('user.self.set_password', compact('user'));
    }

    public function savePassword(SetPasswordRequest $request)
    {
        $user = $request->user();

        $user->password = Hash::make($request->get('password'));
        $user->password_set = true;

        $user->save();

        return redirect()->to('/home');
    }
}
