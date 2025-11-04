<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExerciseController;

Route::get('/exercises', [ExerciseController::class, 'index'])->name('exercises.index');
