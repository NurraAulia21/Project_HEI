<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Dashboard\DashboardController;

//ROUTE DARI BRANCH DEV (NOVI)
Route::prefix('dashboard')->name('dashboard.')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('index');
    
    // Manual input 
    Route::post('/store', [DashboardController::class, 'store'])->name('store');
    
    // Import routes
    Route::get('/import', [DashboardController::class, 'importForm'])->name('import.form');
    Route::post('/import/preview', [DashboardController::class, 'previewCsv'])->name('import.preview');
    Route::post('/import/confirm', [DashboardController::class, 'importCsv'])->name('import.confirm');
    
    Route::delete('/clear', [DashboardController::class, 'clearQuestions'])->name('clear');
    
    // CRUD routes
    Route::get('/{question}/edit', [DashboardController::class, 'edit'])->name('edit');
    Route::put('/{question}', [DashboardController::class, 'update'])->name('update');
    Route::delete('/{question}', [DashboardController::class, 'destroy'])->name('destroy');
    
    // Toggle status route (AJAX)
    Route::post('/{question}/toggle', [DashboardController::class, 'toggleStatus'])->name('toggle');
});

// ROUTE DARI BRANCH nurraaulia
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/HEI-personality-test', function () {
    return view('landing-page');
})->name('hei-personality-test');

Route::get('/test', [TestController::class, 'index'])->middleware('auth')->name('test');
Route::post('/test/submit-answer', [TestController::class, 'submitAnswer'])->name('test.submit-answer');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/HEI-personality-test');
})->name('logout');

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::get('/mbti/intj-architect', function () {
    return view('mbti.intj-architect');
})->name('mbti.intj-architect');

// ========== ROOT PAGE REDIRECT ==========
Route::get('/', function () {
    return redirect()->route('hei-personality-test');
});
