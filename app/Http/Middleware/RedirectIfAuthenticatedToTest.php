<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedToTest
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && $request->route()->getName() === 'hei-personality-test') {
            return redirect()->route('test');
        }

        return $next($request);
    }
}
