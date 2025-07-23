<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\DashboardController;

Route::prefix('dashboard')->name('dashboard.')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('index');
    
    Route::get('/import', [DashboardController::class, 'importForm'])->name('import.form');
    Route::post('/import/preview', [DashboardController::class, 'previewCsv'])->name('import.preview');
    Route::post('/import/confirm', [DashboardController::class, 'importCsv'])->name('import.confirm');
    
    Route::delete('/clear', [DashboardController::class, 'clearQuestions'])->name('clear');
    
    Route::get('/{question}/edit', [DashboardController::class, 'edit'])->name('edit');
    Route::put('/{question}', [DashboardController::class, 'update'])->name('update');
    Route::delete('/{question}', [DashboardController::class, 'destroy'])->name('destroy');
});

Route::get('/', function () {
    return redirect()->route('dashboard.index');
});