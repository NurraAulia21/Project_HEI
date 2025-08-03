<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\QuestionController;
use App\Http\Controllers\Dashboard\AnswerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Root and Landing Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('hei-personality-test');
});

Route::get('/HEI-personality-test', function () {
    return view('landing-page');
})->name('hei-personality-test');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/HEI-personality-test');
})->name('logout');

// Google OAuth
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

/*
|--------------------------------------------------------------------------
| Student Test Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/test', [TestController::class, 'index'])->name('test');
    Route::post('/test/submit-answer', [TestController::class, 'submitAnswer'])->name('test.submit-answer');
    Route::post('/test/submit', [TestController::class, 'submitTest'])->name('test.submit');
    Route::get('/test/retake', [TestController::class, 'retakeTest'])->name('test.retake');
    Route::get('/test/result/{user}/{attempt?}', [TestController::class, 'showResult'])->name('test.result');
});

// MBTI Result Page
// Route::get('/mbti/intj-architect', function () {
//     return view('mbti.intj-architect');
// })->name('mbti.intj-architect');

/*
|--------------------------------------------------------------------------
| Dashboard Routes (dari yang lama)
|--------------------------------------------------------------------------
*/

Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
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
    Route::post('/{question}/toggle', [DashboardController::class, 'toggleStatus'])->name('toggle');
});

/*
|--------------------------------------------------------------------------
| Admin Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'adminIndex'])->name('dashboard');
    
    // Admin Management
    Route::resource('admins', AdminController::class);
    Route::post('admins/{admin}/toggle', [AdminController::class, 'toggleStatus'])->name('admins.toggle');
    
    // Question Management
    Route::get('questions/import/form', [QuestionController::class, 'importForm'])->name('questions.import.form');
    Route::post('questions/import/preview', [QuestionController::class, 'previewCsv'])->name('questions.import.preview');
    Route::post('questions/import/store', [QuestionController::class, 'importCsv'])->name('questions.import.store');
    
    Route::resource('questions', QuestionController::class);
    Route::post('questions/{question}/toggle', [QuestionController::class, 'toggleStatus'])->name('questions.toggle');
    Route::delete('questions/clear/all', [QuestionController::class, 'clearQuestions'])->name('questions.clear');
    Route::post('questions/bulk/toggle', [QuestionController::class, 'bulkToggle'])->name('questions.bulk.toggle');
    Route::post('questions/reorder', [QuestionController::class, 'reorder'])->name('questions.reorder');
    
    // Answer Management
    Route::get('answers', [AnswerController::class, 'index'])->name('answers.index');
    Route::get('answers/{user}', [AnswerController::class, 'show'])->name('answers.show');
    Route::delete('answers/{answer}', [AnswerController::class, 'destroy'])->name('answers.destroy');
    Route::delete('answers/user/{user}', [AnswerController::class, 'destroyUserAnswers'])->name('answers.destroy.user');
    
    Route::post('logout', function () {
        return redirect()->route('admin.login')->with('success', 'Successfully logged out');
    })->name('logout');
});

// Route::get('admin/login', function () {
//     return 'Admin Login page - will be integrated with AuthController';
// })->name('admin.login');

/*
|--------------------------------------------------------------------------
| Admin Login Routes
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware(['web','auth:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.index');
});