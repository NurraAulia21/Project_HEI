<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\QuestionController;
use App\Http\Controllers\Dashboard\AnswerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Root and Landing Routes
|--------------------------------------------------------------------------
*/

// Root redirect
Route::get('/', function () {
    return redirect()->route('hei-personality-test');
});

// Landing page
Route::get('/HEI-personality-test', function () {
    return view('landing-page');
})->name('hei-personality-test');

/*
|--------------------------------------------------------------------------
| Authentication Routes (Branch nurraaulia)
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

Route::get('/test', [TestController::class, 'index'])->middleware('auth')->name('test');
Route::post('/test/submit-answer', [TestController::class, 'submitAnswer'])->name('test.submit-answer');
Route::get('/test/result/{user}/{attempt?}', [TestController::class, 'showResult'])->name('test.result');

// MBTI Result Page
// Route::get('/mbti/intj-architect', function () {
//     return view('mbti.intj-architect');
// })->name('mbti.intj-architect');

/*
|--------------------------------------------------------------------------
| Dashboard Routes (Branch DEV - Novi) - Backward Compatibility
|--------------------------------------------------------------------------
*/

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

/*
|--------------------------------------------------------------------------
| Admin Dashboard Routes (New Implementation)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Home
    Route::get('/', [DashboardController::class, 'adminIndex'])->name('dashboard');
    
    /*
    |--------------------------------------------------------------------------
    | Kelola Admin
    |--------------------------------------------------------------------------
    */
    Route::resource('admins', AdminController::class);
    Route::post('admins/{admin}/toggle', [AdminController::class, 'toggleStatus'])->name('admins.toggle');
    
    /*
    |--------------------------------------------------------------------------
    | Kelola Pertanyaan
    |--------------------------------------------------------------------------
    */
    
    // Import routes (harus sebelum resource routes)
    Route::get('questions/import/form', [QuestionController::class, 'importForm'])->name('questions.import.form');
    Route::post('questions/import/preview', [QuestionController::class, 'previewCsv'])->name('questions.import.preview');
    Route::post('questions/import/store', [QuestionController::class, 'importCsv'])->name('questions.import.store');
    
    // Resource routes
    Route::resource('questions', QuestionController::class);
    
    // Additional routes
    Route::post('questions/{question}/toggle', [QuestionController::class, 'toggleStatus'])->name('questions.toggle');
    Route::delete('questions/clear/all', [QuestionController::class, 'clearQuestions'])->name('questions.clear');
    Route::post('questions/bulk/toggle', [QuestionController::class, 'bulkToggle'])->name('questions.bulk.toggle');
    Route::post('questions/reorder', [QuestionController::class, 'reorder'])->name('questions.reorder');
    
    /*
    |--------------------------------------------------------------------------
    | Kelola Jawaban
    |--------------------------------------------------------------------------
    */
    Route::get('answers', [AnswerController::class, 'index'])->name('answers.index');
    Route::get('answers/{user}', [AnswerController::class, 'show'])->name('answers.show');
    Route::delete('answers/{answer}', [AnswerController::class, 'destroy'])->name('answers.destroy');
    Route::delete('answers/user/{user}', [AnswerController::class, 'destroyUserAnswers'])->name('answers.destroy.user');
    
    /*
    |--------------------------------------------------------------------------
    | Admin Auth (Temporary - akan disesuaikan dengan sistem auth teman)
    |--------------------------------------------------------------------------
    */
    Route::post('logout', function () {
        // Temporary logout logic - nanti akan diintegrasikan dengan AuthController
        return redirect()->route('admin.login')->with('success', 'Successfully logged out');
    })->name('logout');
});

// Admin login route (temporary)
Route::get('admin/login', function () {
    return 'Admin Login page - will be integrated with AuthController';
})->name('admin.login');