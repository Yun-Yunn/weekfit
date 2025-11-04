<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\ExerciseImage;
use App\Models\Muscle;
use App\Models\Equipment;

class DashboardController extends Controller
{
    public function index()
    {

        $stats = [
            'exercises' => Exercise::count(),
            'images' => ExerciseImage::count(),
            'muscles' => Muscle::count(),
            'equipments' => Equipment::count(),
        ];

        return view('dashboard', compact('stats'));
    }
}
