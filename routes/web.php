<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\TestController;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/HEI-personality-test', function () {
    return view('landing-page');
})->name('hei-personality-test');

Route::get('/test', function () {
    return view('test');
})->middleware('auth')->name('test');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/HEI-personality-test');
})->name('logout');

// Route::get('/auth/google', function () {
//     return Socialite::driver('google')->redirect();
// })->name('google.login');
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::get('/test', [TestController::class, 'index'])->name('test');
Route::post('/dashboard/submit-answer', [TestController::class, 'submitAnswer'])->name('dashboard.submit-answer');

Route::get('/mbti/intj-architect', function () {
    return view('mbti.intj-architect');
})->name('mbti.intj-architect');