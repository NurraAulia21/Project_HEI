<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', [TestController::class, 'index'])->name('test');
Route::post('/test/submit-answer', [TestController::class, 'submitAnswer'])->name('test.submit-answer');