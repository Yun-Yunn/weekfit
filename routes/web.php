<?php

use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\StatsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// ✅ routes protégées Breeze
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/exercises', [ExerciseController::class, 'index'])->name('exercises.index');
    Route::get('/stats', [StatsController::class, 'index'])->name('stats.index');
});

require __DIR__ . '/auth.php';
