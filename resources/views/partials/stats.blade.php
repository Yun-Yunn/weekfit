<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(145deg, #1a1a1a, #222);
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
            padding: 1rem;
        }

        h2 {
            text-align: center;
            font-weight: 700;
            margin-bottom: 2rem;
            color: #f8f9fa;
        }

        .card {
            border: none;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0 20px rgba(255,255,255,0.2);
        }

        .card h5 {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .card p {
            font-size: 1.6rem;
            font-weight: bold;
        }

        /* 🎨 Couleurs personnalisées */
        .card-exo { background: linear-gradient(135deg, #1f77b4, #6fa8dc); }
        .card-img { background: linear-gradient(135deg, #28a745, #6dd47e); }
        .card-muscle { background: linear-gradient(135deg, #e67e22, #f1c40f); }
        .card-equip { background: linear-gradient(135deg, #9b59b6, #b98ff2); }

        .refresh-btn {
            background: linear-gradient(90deg, #007bff, #00c3ff);
            border: none;
            width: 100%;
            padding: 0.8rem;
            border-radius: 10px;
            font-weight: bold;
            color: white;
            transition: 0.2s ease;
        }

        .refresh-btn:hover {
            opacity: 0.85;
        }
    </style>
</head>
<body>

    <h2>📊 Statistiques générales</h2>

    <div class="card card-exo text-white">
        <h5>Exercices enregistrés</h5>
        <p>{{ $stats['exercises'] ?? 0 }}</p>
    </div>

    <div class="card card-img text-white">
        <h5>Images associées</h5>
        <p>{{ $stats['images'] ?? 0 }}</p>
    </div>

    <div class="card card-muscle text-dark">
        <h5>Muscles distincts</h5>
        <p>{{ $stats['muscles'] ?? 0 }}</p>
    </div>

    <div class="card card-equip text-white">
        <h5>Équipements distincts</h5>
        <p>{{ $stats['equipments'] ?? 0 }}</p>
    </div>

    <button class="refresh-btn mt-3" onclick="location.reload()">🔄 Actualiser</button>

</body>
</html>
