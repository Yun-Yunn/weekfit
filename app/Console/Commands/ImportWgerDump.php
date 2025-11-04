<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Muscle;
use App\Models\Equipment;
use App\Models\Exercise;
use App\Models\ExerciseCategory;
use App\Models\ExerciseImage;

class WgerFullSeeder extends Seeder
{
    public function run()
    {
        ini_set('memory_limit', '1024M');
        $this->command->info('⚡ Importation complète et optimisée des données WGER...');

        $this->importEndpoint(Muscle::class, 'muscle', function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name_en'] ?? $item['name'] ?? 'Unnamed muscle',
            ];
        });


        $this->importEndpoint(Equipment::class, 'equipment', function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'] ?? 'Unnamed equipment',
            ];
        });


        $this->importEndpoint(ExerciseCategory::class, 'exercisecategory', function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'] ?? 'Unnamed category',
            ];
        });

        $this->command->info('➡️ Import des exercices (toutes langues, sans limite)...');

        $page = 1;
        $totalCount = 0;

        do {
            $response = Http::withoutVerifying()
                ->get("https://wger.de/api/v2/exerciseinfo/?language=2&limit=5000&page=$page")
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

            unset($results, $response);
            gc_collect_cycles();

            $page++;
        } while (!empty($response['next']));

        $this->command->info("✅ $totalCount exercices importés (anglais).");

        $this->command->info('➡️ Import des traductions anglaises...');

        $page = 1;
        $translated = 0;

        do {
            $response = Http::withoutVerifying()
                ->get("https://wger.de/api/v2/exercise-translation/?language=2&limit=5000&page=$page")
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

        $this->command->info('➡️ Import des images d’exercices...');

        $page = 1;
        $totalImages = 0;

        do {
            $response = Http::withoutVerifying()
                ->get("https://wger.de/api/v2/exerciseimage/?limit=5000&page=$page")
                ->json();

            $results = $response['results'] ?? [];

            foreach ($results as $image) {
                ExerciseImage::updateOrCreate(
                    ['id' => $image['id']],
                    [
                        'exercise_id' => $image['exercise'] ?? null,
                        'image' => $image['image'] ?? '',
                        'is_main' => $image['is_main'] ?? false,
                    ]
                );
                $totalImages++;
            }

            $this->command->info("   → Page $page : " . count($results) . " images importées");

            unset($results, $response);
            gc_collect_cycles();

            $page++;
        } while (!empty($response['next']));

        $this->command->info("✅ $totalImages images d’exercices importées.");

        $this->command->info("📊 Résumé total :");
        $this->command->info("  • Muscles : " . Muscle::count());
        $this->command->info("  • Équipements : " . Equipment::count());
        $this->command->info("  • Catégories : " . ExerciseCategory::count());
        $this->command->info("  • Exercices : " . Exercise::count());
        $this->command->info("  • Images : " . ExerciseImage::count());

        $this->command->info('🏁 Importation complète terminée avec succès !');
    }


    private function importEndpoint($modelClass, $endpoint, $map)
    {
        $this->command->info("➡️ Import de $endpoint...");

        $page = 1;
        $count = 0;

        do {
            $response = Http::withoutVerifying()
                ->get("https://wger.de/api/v2/$endpoint/?limit=5000&page=$page")
                ->json();

            $results = $response['results'] ?? [];

            foreach ($results as $item) {
                $data = $map($item);
                $modelClass::updateOrCreate(['id' => $data['id']], $data);
                $count++;
            }

            $this->command->info("   → Page $page : " . count($results) . " $endpoint importés");

            unset($results);
            gc_collect_cycles();

            $page++;
        } while (!empty($response['next']));

        $this->command->info("✅ $count $endpoint importés au total");
    }
}
