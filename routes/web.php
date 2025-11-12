<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StatsController;
use Illuminate\Support\Facades\Http;



Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');



Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware('auth')->group(function () {

    Route::get('/exercises', [ExerciseController::class, 'index'])->name('exercises.index');
    Route::get('/stats', [StatsController::class, 'index'])->name('stats.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});
Route::get('/test-translate', function () {
    $response = Http::get('https://translate.googleapis.com/translate_a/single', [
        'client' => 'gtx',
        'sl' => 'auto',
        'tl' => 'fr',
        'dt' => 't',
        'q' => 'Hello, how are you?',
    ]);

    return $response->json();
});


require __DIR__ . '/auth.php';
