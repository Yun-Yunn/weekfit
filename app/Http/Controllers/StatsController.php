<?php

namespace App\Http\Controllers;
 
use App\Models\Exercise;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;

class StatsController extends Controller
{
    public function index(): View 
    {
        // Récupération de l'ID de l'utilisateur connecté
        $userId = auth()->id(); 
        
        // 1. Définition de la période hebdomadaire (7 derniers jours complets)
        $startOfPeriod = Carbon::now()->subDays(7)->startOfDay();
        $endOfPeriod = Carbon::now()->endOfDay();              

        // 2. Récupération des exercices de l'utilisateur dans la semaine (filtrés par user_id et created_at)
        $weeklyExercises = Exercise::where('user_id', $userId) 
                                    ->whereBetween('created_at', [$startOfPeriod, $endOfPeriod])
                                    ->get();

        // 3. Calcul des statistiques hebdomadaires (uniquement celles que vous souhaitez conserver)
        $stats = [
            // Statistiques Hebdomadaires de l'utilisateur (7 derniers jours)
            'weekly_exercises' => $weeklyExercises->count(),
            'weekly_muscles' => $weeklyExercises->pluck('muscle_id')->unique()->count(), 
            'weekly_equipments' => $weeklyExercises->pluck('equipment_id')->unique()->count(), 
        ];

        // 4. Préparation des données pour le graphique (Exercices par jour sur 7 jours)
        $dailyExercises = $weeklyExercises->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('Y-m-d');
        })->map(function ($item, $key) {
            return count($item); 
        });

        // 5. Génération des étiquettes (jours) et des données (comptes)
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