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
            ->whereHas('images')
            ->orderBy('id', 'asc')
            ->get();


        foreach ($exercises as $exercise) {
            $cacheKey = 'exercise_translation_' . $exercise->id;

            [$translatedName, $translatedDesc] = Cache::remember($cacheKey, now()->addDays(7), function () use ($exercise) {
                try {
                    $apiUrl = 'https://libretranslate.de/translate';


                    $translatedName = Http::timeout(15)->post($apiUrl, [
                        'q' => $exercise->name,
                        'source' => 'auto',
                        'target' => 'fr',
                        'format' => 'text'
                    ])->json()['translatedText'] ?? $exercise->name;

                    $translatedDesc = Http::timeout(15)->post($apiUrl, [
                        'q' => strip_tags($exercise->description),
                        'source' => 'auto',
                        'target' => 'fr',
                        'format' => 'text'
                    ])->json()['translatedText'] ?? $exercise->description;


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


    private static function isFrench($text)
    {
        $frenchWords = ['le', 'la', 'les', 'des', 'est', 'avec', 'pour', 'une', 'dans', 'vous'];
        $count = 0;

        foreach ($frenchWords as $word) {
            if (stripos($text, $word) !== false) {
                $count++;
            }
        }

        return $count >= 3;
    }
}
