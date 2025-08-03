<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Sync sessionStorage di JS
            $request->session()->flash('set_hei_logged_in', true);

            return redirect()->route('test');
        }

        return back()->withErrors([
            'username' => 'Username or password is incorrect.',
        ]);
    }



    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);
        User::create([
            'username' => $request->input('username'),
            'email'    => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
        return back()->with('registered', true);
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $findUser = User::where('google_id', $googleUser->id)->first();

        if (!$findUser) {
            // Buat username otomatis dari email
            $baseUsername = explode('@', $googleUser->email)[0];
            $username = $baseUsername;
            $counter = 1;

            // Pastikan username unik
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }

            $findUser = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'username' => $username,
                'password' => bcrypt(Str::random(16)), // password dummy
            ]);
        }

        Auth::login($findUser);

        session()->regenerate();
        session()->flash('set_hei_logged_in', true); // untuk pop-up JS jika perlu

        return redirect()->route('test');
    }

    public function logout(Request $request)
    {
        \Log::debug('Before logout', [
            'session_id' => session()->getId(),
            'auth' => Auth::check()
        ]);

        Auth::logout();
        session()->flush(); 

        \Log::debug('After logout', [
            'session_id' => session()->getId(),
            'auth' => Auth::check()
        ]);

        session()->forget('skip_login_popup');

        // Kirim sinyal untuk hapus client-side sessionStorage
        return redirect()->route('hei-personality-test')
        ->with('clear_session_storage', true);
    }

    protected function redirectTo($request): ?string
    {
        \Log::debug('Redirecting unauthenticated', [
            'url' => $request->fullUrl(),
            'session_id' => session()->getId(),
            'cookies' => $request->cookies->all(),
            'auth' => \Auth::check(),
        ]);
    
        return route('hei-personality-test');
    }

}
