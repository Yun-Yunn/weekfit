<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard WeekFit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #1e1e1e;
            color: #fff;
            overflow: hidden;
        }
        .dashboard {
            display: flex;
            flex-direction: row;
            height: 100vh;
            gap: 1rem;
            padding: 1rem;
        }
        .left-panel, .right-panel {
            background-color: #2b2b2b;
            border-radius: 10px;
            padding: 1rem;
        }
        .left-panel {
            flex: 1.2;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .right-panel {
            flex: 2.5;
            overflow: hidden;
        }
        iframe {
            width: 100%;
            height: 100%;
            border: none;
            border-radius: 10px;
            background: #1e1e1e;
        }
        h2 {
            text-align: center;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

<div class="dashboard">

    {{-- 🧮 Panneau des statistiques (chargé via StatsController) --}}
    <div class="left-panel">
        <iframe src="{{ route('stats.index') }}" title="Statistiques"></iframe>
    </div>

    {{-- 🏋️‍♂️ Panneau des exercices (ton module actuel) --}}
    <div class="right-panel">
        <iframe src="{{ route('exercises.index') }}" title="Exercices"></iframe>
    </div>

</div>

</body>
</html>
