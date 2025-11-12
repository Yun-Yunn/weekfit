<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Exercice du jour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1e1e1e;
            color: #f5f5f5;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
        }
        .card img {
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            max-height: 400px;
            object-fit: cover;
        }
        .card-body {
            background-color: #3b3b3b;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
        }
        h1 {
            font-weight: 700;
        }
    </style>
</head>
<body>

<div class="container mt-4 text-center">
    <h1 class="mb-4">🏋️‍♂️ Exercice du jour</h1>

    @if(!$exercise)
        <div class="alert alert-warning">
            Aucun exercice trouvé.
        </div>
    @else
        <div class="card text-white mx-auto" style="max-width: 600px;">
            @if($exercise->images->first())
                <img src="{{ $exercise->images->first()->image }}" 
                     alt="{{ $exercise->translated_name }}" 
                     class="card-img-top">
            @endif

            <div class="card-body">
                <h3 class="card-title mb-3 fw-bold">
                    {{ $exercise->translated_name ?? $exercise->name }}
                </h3>

                <p class="card-text text-light" style="text-align: justify;">
                    {{ $exercise->translated_description ?? $exercise->description }}
                </p>

                <hr class="border-light">

                <p class="mb-1">
                    💪 <strong>Muscle :</strong> 
                    {{ $exercise->translated_muscle ?? ($exercise->muscle->name ?? 'Inconnu') }}
                </p>
                <p>
                    🏋️ <strong>Équipement :</strong> 
                    {{ $exercise->translated_equipment ?? ($exercise->equipment->name ?? 'Aucun') }}
                </p>
            </div>
        </div>
    @endif
</div>

</body>
</html>
