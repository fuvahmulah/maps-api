<?php

namespace App\Http\Middleware;

use Closure;

class CheckPasswordSet
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $user = $request->user();

        if (!$user->password_set) {
            return redirect()->route('me.set-password');
        }

        return $next($request);
    }
}
