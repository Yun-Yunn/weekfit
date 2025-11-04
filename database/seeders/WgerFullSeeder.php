<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Muscle;
use App\Models\Equipment;
use App\Models\Exercise;
use App\Models\ExerciseCategory;

class WgerFullSeeder extends Seeder
{
    public function run()
    {
        // 🔧 Augmente la mémoire max pour éviter les erreurs "Allowed memory size exhausted"
        ini_set('memory_limit', '512M');

        $this->command->info('⚡ Importation complète et optimisée des données WGER...');

        // 1️⃣ Import Muscles
        $this->importEndpoint(Muscle::class, 'muscle', function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name_en'] ?? $item['name'] ?? 'Unnamed muscle',
            ];
        });

        // 2️⃣ Import Equipment
        $this->importEndpoint(Equipment::class, 'equipment', function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'] ?? 'Unnamed equipment',
            ];
        });

        // 3️⃣ Import Categories
        $this->importEndpoint(ExerciseCategory::class, 'exercisecategory', function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'] ?? 'Unnamed category',
            ];
        });

        // 4️⃣ Import Exercises (anglais uniquement, sans images)
        $this->command->info('➡️ Import des exercices (en anglais, sans images)...');

        $page = 1;
        $totalCount = 0;

        do {
            $response = Http::withoutVerifying()
                ->get("https://wger.de/api/v2/exerciseinfo/?language=2&limit=100&page=$page")
                ->json();

            $results = $response['results'] ?? [];

            foreach ($results as $exercise) {
                $totalCount++;

                Exercise::updateOrCreate(
                    ['id' => $exercise['id']],
                    [
                        'name' => $exercise['name'] ?? $exercise['name_original'] ?? 'Unnamed exercise',
                        'description' => $exercise['description'] ?? '',
                        'muscle_id' => $exercise['muscles'][0]['id'] ?? null,
                        'equipment_id' => $exercise['equipment'][0]['id'] ?? null,
                    ]
                );
            }

            $this->command->info("   → Page $page : " . count($results) . " exercices importés");

            // 🧹 Nettoyage mémoire
            unset($results, $response);
            gc_collect_cycles();

            $page++;
        } while (!empty($response['next']));

        $this->command->info("✅ $totalCount exercices importés (anglais).");

        // 5️⃣ Import des traductions anglaises (nom + description)
        $this->command->info('➡️ Import des traductions (anglais)...');

        $page = 1;
        $translated = 0;

        do {
            $response = Http::withoutVerifying()
                ->get("https://wger.de/api/v2/exercise-translation/?language=2&limit=100&page=$page")
                ->json();

            $results = $response['results'] ?? [];

            foreach ($results as $translation) {
                $exerciseId = $translation['exercise'] ?? null;
                if (!$exerciseId) continue;

                Exercise::where('id', $exerciseId)->update([
                    'name' => $translation['name'] ?? 'Unnamed exercise',
                    'description' => $translation['description'] ?? '',
                ]);

                $translated++;
            }

            $this->command->info("   → Page $page : " . count($results) . " traductions appliquées");

            unset($results, $response);
            gc_collect_cycles();
            $page++;
        } while (!empty($response['next']));

        $this->command->info("✅ $translated traductions anglaises appliquées.");

        $this->command->info('🏁 Importation complète terminée avec succès !');
    }

    /**
     * Import générique d'un endpoint simple (muscles, équipement, catégories)
     */
    private function importEndpoint($modelClass, $endpoint, $map)
    {
        $this->command->info("➡️ Import de $endpoint...");

        $response = Http::withoutVerifying()
            ->get("https://wger.de/api/v2/$endpoint/?limit=200")
            ->json()['results'] ?? [];

        foreach ($response as $item) {
            $data = $map($item);
            $modelClass::updateOrCreate(['id' => $data['id']], $data);
        }

        $this->command->info("   → " . count($response) . " $endpoint importés");
    }
}
