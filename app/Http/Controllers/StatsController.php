<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View; // Importation pour le type de retour
use Illuminate\Http\Request;

use App\Models\Equipment;
use App\Models\Exercise;
use App\Models\ExerciseImage;
use App\Models\Muscle;
use Carbon\Carbon;

class StatsController extends Controller
{
    public function index(): View 
    {
        // 1. Définition de la période hebdomadaire (7 derniers jours)
        $startOfPeriod = Carbon::now()->subDays(7)->startOfDay(); 
        $endOfPeriod = Carbon::now()->endOfDay();               

        // 2. Récupération des exercices de la semaine (les 7 derniers jours)
        // La colonne 'created_at' est utilisée ici, ce qui est correct.
        $weeklyExercises = Exercise::whereBetween('created_at', [$startOfPeriod, $endOfPeriod])
                                    ->get();

        $stats = [
            // ... autres stats ...
            
            // Statistiques Hebdomadaires (7 derniers jours)
            'weekly_exercises' => $weeklyExercises->count(),
            'weekly_muscles' => $weeklyExercises->pluck('muscle_id')->unique()->count(), 
            'weekly_equipments' => $weeklyExercises->pluck('equipment_id')->unique()->count(), 
            'weekly_images' => ExerciseImage::whereBetween('created_at', [$startOfPeriod, $endOfPeriod])->count(),
        ];

        // 3. Préparation des données pour le graphique
        $dailyExercises = $weeklyExercises->groupBy(function($date) {
            // Utilise 'created_at' pour le regroupement, ce qui est correct.
            return Carbon::parse($date->created_at)->format('Y-m-d'); 
        })->map(function ($item, $key) {
            return count($item); 
        });

        // ... logique pour remplir les jours manquants ...
        $labels = [];
        $data = [];
        for ($i = 6; $i >= 0; $i--) { 
            $date = Carbon::now()->subDays($i);
            $formattedDate = $date->format('Y-m-d');
            $labels[] = $date->format('D d/m'); 
            $data[] = $dailyExercises->get($formattedDate, 0); 
        }

        $stats['chart_labels'] = $labels;
        $stats['chart_data'] = $data;

        return view('partials.stats', data: compact('stats'));
    }
}