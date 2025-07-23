<?php
namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
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
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);
        User::create([
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
        return back()->with('registered', true);
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'username' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(str()->random(16)), // random password
            ]
        );

        Auth::login($user);
        return redirect()->route('test'); // Redirect ke halaman test
    }
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            //->with(['prompt' => 'select_account', 'hd' => 'student.telkomuniversity.ac.id']) //kalau mau pake sso
            ->with(['prompt' => 'select_account']) // email pribadi
            ->redirect();
    }

}