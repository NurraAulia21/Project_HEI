<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckAuth
{
    public function handle($request, Closure $next)
    {
        logger('Auth check:', [
            'auth' => Auth::check(),
            'user' => Auth::user(),
            'session_id' => Session::getId(),
        ]);

        return $next($request);
    }
}
