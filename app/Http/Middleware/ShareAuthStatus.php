<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ShareAuthStatus
{
    public function handle($request, Closure $next)
    {
        view()->share('isLoggedIn', Auth::check() || $request->session()->get('skip_login_popup'));
        return $next($request);
    }
}
