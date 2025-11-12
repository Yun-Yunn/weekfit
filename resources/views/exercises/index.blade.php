<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des exercices</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
     <style>
    body {
    background-color: #22223b;
    color: white;
    }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h1 class="mb-4">🏋️‍♂️ Exercices WGER importés</h1>

        <div class="row">
            @foreach ($exercises as $exercise)
                <div class="col-md-4 mb-4">
                    <div class="card bg-secondary text-white h-100">
                        @if ($exercise->images->first())
                            <img src="{{ $exercise->images->first()->image ?? 'https://via.placeholder.com/400x300?text=No+Image' }}"
                                class="card-img-top" alt="{{ $exercise->name }}">
                        @else
                            <img src="https://via.placeholder.com/400x300?text=No+Image" class="card-img-top">
                        @endif

                        <div class="card-body">
                            <h5 class="card-title">{{ $exercise->name ?? 'Sans nom' }}</h5>
                            <p class="card-text">{!! Str::limit(strip_tags($exercise->description), 100) !!}</p>
                            <p>
                                💪 <strong>Muscle :</strong> {{ $exercise->muscle->name ?? 'Inconnu' }}<br>
                                🏋️ <strong>Équipement :</strong> {{ $exercise->equipment->name ?? 'Aucun' }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>

</html>
