<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exercise;
use Stichoza\GoogleTranslate\GoogleTranslate;

class ExerciseController extends Controller
{
    public function index()
    {
        // 🧠 Récupère un exercice aléatoire AVEC une image .gif
        $exercise = Exercise::with(['images', 'muscle', 'equipment'])
            ->whereHas('images', function ($query) {
                $query->where('image', 'LIKE', '%.gif');
            })
            ->inRandomOrder()
            ->first();

        if (!$exercise) {
            return view('exercises.index', ['exercise' => null]);
        }

        // 🌍 Initialisation du traducteur local
        $tr = new GoogleTranslate('fr'); // traduction vers le français
        $tr->setSource('auto'); // détection automatique de la langue

        // ⚡ Fonction de traduction locale
        $translate = function ($text) use ($tr) {
            if (!$text || trim($text) === '') return '—';
            try {
                return $tr->translate(strip_tags($text));
            } catch (\Throwable $e) {
                info('Erreur traduction : ' . $e->getMessage());
                return $text; // fallback si erreur
            }
        };

        // 🪄 Traduction des champs
        $exercise->translated_name = $translate($exercise->name);
        $exercise->translated_description = $translate($exercise->description);
        $exercise->translated_muscle = $translate(optional($exercise->muscle)->name ?? 'Inconnu');
        $exercise->translated_equipment = $translate(optional($exercise->equipment)->name ?? 'Aucun');

        // ✅ Envoi à la vue
        return view('exercises.index', compact('exercise'));
    }
}
