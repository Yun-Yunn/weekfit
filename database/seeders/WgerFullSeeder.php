<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Exercise;
use App\Models\Muscle;
use App\Models\Equipment;
use App\Models\ExerciseImage;

class WgerFullSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🚀 Import complet de la base Wger vers MySQL...');

        // 1️⃣ Muscles
        $muscles = Http::withoutVerifying()->get('https://wger.de/api/v2/muscle/')->json()['results'] ?? [];
        foreach ($muscles as $m) {
            Muscle::updateOrCreate(['id' => $m['id']], ['name' => $m['name']]);
        }
        $this->command->info('✅ Muscles importés : ' . count($muscles));

        // 2️⃣ Équipements
        $equipments = Http::withoutVerifying()->get('https://wger.de/api/v2/equipment/')->json()['results'] ?? [];
        foreach ($equipments as $e) {
            Equipment::updateOrCreate(['id' => $e['id']], ['name' => $e['name']]);
        }
        $this->command->info('✅ Équipements importés : ' . count($equipments));

        // 3️⃣ Exercices (import paginé)
        $page = 0;
        $total = 0;
        do {
            $url = "https://wger.de/api/v2/exercise/?language=8&limit=100&offset=" . ($page * 100);

            $response = Http::withoutVerifying()->get($url);

            // Vérifie que la requête a réussi
            if ($response->failed()) {
                $this->command->warn("⚠️ Erreur HTTP page $page");
                break;
            }

            $json = $response->json();
            $results = $json['results'] ?? [];

            if (empty($results)) {
                $this->command->warn("⚠️ Aucune donnée reçue pour la page $page");
                break;
            }

            foreach ($results as $ex) {
                if (!isset($ex['id']) || empty($ex['name'])) {
                    continue; // On ignore les exercices incomplets
                }

                Exercise::updateOrCreate(
                    ['id' => $ex['id']],
                    [
                        'name' => $ex['name'],
                        'description' => strip_tags($ex['description'] ?? ''),
                        'muscle_id' => $ex['muscles'][0] ?? null,
                        'equipment_id' => $ex['equipment'][0] ?? null,
                    ]
                );
                $total++;
            }

            $page++;
        } while (!empty($json['next']));

        $this->command->info("✅ Exercices importés : $total");


        // 4️⃣ Images
        $page = 0;
        $totalImg = 0;
        do {
            $url = "https://wger.de/api/v2/exerciseimage/?limit=100&offset=" . ($page * 100);
            $response = Http::withoutVerifying()->get($url)->json();
            $results = $response['results'] ?? [];

            foreach ($results as $img) {
                ExerciseImage::updateOrCreate(
                    ['id' => $img['id']],
                    [
                        'exercise_id' => $img['exercise'],
                        'image' => $img['image'],
                        'is_main' => $img['is_main']
                    ]
                );
                $totalImg++;
            }

            $page++;
        } while (!empty($response['next']));

        $this->command->info("🖼️ Images importées : $totalImg");
        $this->command->info('🎉 Import complet terminé !');
    }
}
