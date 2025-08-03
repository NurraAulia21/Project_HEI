<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\AdminAuthController;
use Illuminate\Support\Facades\Auth;

Route::middleware('web')->group(function () {
    // Dashboard (dari branch dev)
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::post('/store', [DashboardController::class, 'store'])->name('store');
        Route::get('/import', [DashboardController::class, 'importForm'])->name('import.form');
        Route::post('/import/preview', [DashboardController::class, 'previewCsv'])->name('import.preview');
        Route::post('/import/confirm', [DashboardController::class, 'importCsv'])->name('import.confirm');
        Route::delete('/clear', [DashboardController::class, 'clearQuestions'])->name('clear');
        Route::get('/{question}/edit', [DashboardController::class, 'edit'])->name('edit');
        Route::put('/{question}', [DashboardController::class, 'update'])->name('update');
        Route::delete('/{question}', [DashboardController::class, 'destroy'])->name('destroy');
        Route::post('/{question}/toggle', [DashboardController::class, 'toggleStatus'])->name('toggle');
    });

    // Auth
    Route::get('/login', fn() => redirect()->route('hei-personality-test')->with('showLogin', true))->name('login.show');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Root redirect
    Route::get('/', function () {
        if (Auth::check()) {
            return redirect()->route('test');
        }
        return redirect()->route('hei-personality-test');
    });

    // Landing page
    Route::get('/HEI-personality-test', function () {
        if (Auth::check()) {
            return redirect()->route('test');
        }
        return view('landing-page')->with('showLogin', session('showLogin', false));
    })->name('hei-personality-test');

    // Landing page
    //Route::get('/HEI-personality-test', fn() => view('landing-page'))->name('hei-personality-test');

    // Test
    Route::get('/test', [TestController::class, 'index'])->name('test');
    Route::post('/test/submit-answer', [TestController::class, 'submitAnswer'])
    ->name('test.submit-answer')
    ->middleware('auth');

    // Google OAuth
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

    // MBTI Example
    Route::get('/mbti/intj-architect', fn() => view('mbti.intj-architect'))->name('mbti.intj-architect');

    // Root redirect
    Route::get('/', fn() => redirect()->route('hei-personality-test'));
});

// Admin routes (pakai guard admin)
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware(['web','auth:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
});
