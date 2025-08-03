<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AllowTest
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            \Log::debug('AllowTest: akses diizinkan', [
                'auth' => true,
                'session_id' => session()->getId(),
            ]);
            return $next($request);
        }

        \Log::debug('AllowTest: akses ditolak', [
            'auth' => false,
            'session_id' => session()->getId(),
        ]);

        return redirect()->route('hei-personality-test');
    }
}
