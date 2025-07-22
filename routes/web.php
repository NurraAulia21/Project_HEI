<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', [TestController::class, 'index'])->name('test');
Route::post('/dashboard/submit-answer', [TestController::class, 'submitAnswer'])->name('dashboard.submit-answer');