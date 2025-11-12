<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exercise;
use Stichoza\GoogleTranslate\GoogleTranslate;

class ExerciseController extends Controller
{
    public function index()
    {
        // 🧠 Récupère 5 exercices aléatoires avec au moins une image .gif
        $exercises = Exercise::with(['images', 'muscle', 'equipment'])
            ->whereHas('images', function ($query) {
                $query->where('image', 'LIKE', '%.gif');
            })
            ->inRandomOrder()
            ->limit(5)
            ->get();

        if ($exercises->isEmpty()) {
            return view('exercises.index', ['exercises' => collect()]);
        }

        // 🌍 Initialisation du traducteur local
        $tr = new GoogleTranslate('fr');
        $tr->setSource('auto');

        // ⚡ Fonction de traduction sécurisée
        $translate = function ($text) use ($tr) {
            if (!$text || trim($text) === '') return '—';
            try {
                return $tr->translate(strip_tags($text));
            } catch (\Throwable $e) {
                info('Erreur traduction : ' . $e->getMessage());
                return $text;
            }
        };

        // 🪄 Traduit tous les exercices
        foreach ($exercises as $exercise) {
            $exercise->translated_name = $translate($exercise->name);
            $exercise->translated_description = $translate($exercise->description);
            $exercise->translated_muscle = $translate(optional($exercise->muscle)->name ?? 'Inconnu');
            $exercise->translated_equipment = $translate(optional($exercise->equipment)->name ?? 'Aucun');
        }

        // ✅ Envoie les 5 exercices à la vue
        return view('exercises.index', compact('exercises'));
    }
}
