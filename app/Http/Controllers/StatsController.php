<?php

namespace App\Http\Controllers;

use App\Models\Equipment; 
use App\Models\Exercise;
use App\Models\ExerciseImage;
use App\Models\Muscle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class StatsController extends Controller
{
    public function index(): View
    {
        // 1. Définition de la période hebdomadaire (7 derniers jours)
        // La date de début est il y a 7 jours
        $startOfPeriod = Carbon::now()->subDays(7); 
        // La date de fin est maintenant
        $endOfPeriod = Carbon::now();

        // 2. Récupération des exercices de la semaine (les 7 derniers jours)
        $weeklyExercises = Exercise::whereBetween('created_at', [$startOfPeriod, $endOfPeriod])
                                    ->get();

        $stats = [
            // Statistiques Hebdomadaires (7 derniers jours)
            // L'ajout de ces clés permettra de les afficher dans la vue
            'weekly_exercises' => $weeklyExercises->count(),
            // Optionnel : Compter les muscles distincts de la semaine pour l'encadré "Muscles distincts"
            'weekly_muscles' => $weeklyExercises->pluck('muscle_id')->unique()->count(), 
            // Optionnel : Compter les équipements distincts de la semaine pour l'encadré "Équipements distincts"
            'weekly_equipments' => $weeklyExercises->pluck('equipment_id')->unique()->count(), 
        ];

        // 3. Préparation des données pour le graphique (Exercices par jour sur les 7 derniers jours)
        $dailyExercises = $weeklyExercises->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('Y-m-d'); // Grouper par jour
        })->map(function ($item, $key) {
            return count($item); // Compter les exercices par jour
        });

        // Remplir les jours manquants avec 0 pour avoir 7 jours complets
        $labels = [];
        $data = [];
        for ($i = 6; $i >= 0; $i--) { // Pour les 7 derniers jours (aujourd'hui inclus)
            $date = Carbon::now()->subDays($i);
            $formattedDate = $date->format('Y-m-d');
            $labels[] = $date->format('D d/m'); // ex: Lun 25/10
            $data[] = $dailyExercises->get($formattedDate, 0); // Récupérer la valeur, 0 si absente
        }

        // Ajout des données du graphique au tableau $stats
        $stats['chart_labels'] = $labels;
        $stats['chart_data'] = $data;

        return view('partials.stats', data: compact('stats'));
        
    }
}

