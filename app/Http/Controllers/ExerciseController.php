<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ExerciseController extends Controller
{
    public function index()
    {
        $exercises = Exercise::with(['muscle', 'equipment', 'images'])
            ->whereNotNull('name')
            ->where('name', '<>', 'Unnamed exercise')
            ->whereNotNull('description')
            ->where('description', '<>', '')
            ->take(10)
            ->get();

        foreach ($exercises as $exercise) {
            $cacheKey = 'exercise_translation_' . $exercise->id;

            [$translatedName, $translatedDesc] = Cache::remember($cacheKey, now()->addDays(7), function () use ($exercise) {
                try {
                    // 🌍 API LibreTranslate (plus fiable)
                    $apiUrl = 'https://libretranslate.de/translate';

                    // 🔤 Traduction du nom
                    $translatedName = Http::timeout(15)->post($apiUrl, [
                        'q' => $exercise->name,
                        'source' => 'auto',
                        'target' => 'fr',
                        'format' => 'text'
                    ])->json()['translatedText'] ?? $exercise->name;

                    // 🔤 Traduction de la description
                    $translatedDesc = Http::timeout(15)->post($apiUrl, [
                        'q' => strip_tags($exercise->description),
                        'source' => 'auto',
                        'target' => 'fr',
                        'format' => 'text'
                    ])->json()['translatedText'] ?? $exercise->description;

                    // 🧠 Vérifie si la traduction est incohérente (anglais, espagnol, turc...)
                    if (!self::isFrench($translatedDesc)) {
                        $translatedDesc = Http::timeout(15)->post($apiUrl, [
                            'q' => $translatedDesc,
                            'source' => 'auto',
                            'target' => 'fr',
                            'format' => 'text'
                        ])->json()['translatedText'] ?? $translatedDesc;
                    }

                    return [$translatedName, $translatedDesc];
                } catch (\Throwable $e) {
                    info('Erreur traduction : ' . $e->getMessage());
                    return [$exercise->name, $exercise->description];
                }
            });

            $exercise->name = $translatedName;
            $exercise->description = $translatedDesc;
        }

        return view('exercises.index', compact('exercises'));
    }

    /**
     * Vérifie si un texte semble être en français
     */
    private static function isFrench($text)
    {
        // mots fréquents du français pour détecter la langue
        $frenchWords = ['le', 'la', 'les', 'des', 'est', 'avec', 'pour', 'une', 'dans', 'vous'];
        $count = 0;

        foreach ($frenchWords as $word) {
            if (stripos($text, $word) !== false) {
                $count++;
            }
        }

        // si au moins 3 mots FR trouvés → c’est français
        return $count >= 3;
    }
}
