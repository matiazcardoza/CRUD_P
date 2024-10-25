<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIfUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->activo) {
            // Logout the user and redirect them with an error message
            Auth::logout();

            return redirect()->route('login')->withErrors([
                'email' => 'Tu cuenta está inactiva.',
            ]);
        }

        return $next($request);
    }
}
