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
        .navbar {
            background-color: #1a1a2e;
            border-bottom: 1px solid #444;
            height: 60px;
        }
        .dashboard {
            display: flex;
            flex-direction: row;
            height: calc(100vh - 60px);
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
        .date {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            font-weight: 500;
            color: #f5ebeb;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark px-3">
    <div class="container-fluid">
        <span class="navbar-brand">WeekFit</span>

        <span class="date">
            {{ \Illuminate\Support\Str::title(now()->translatedFormat('l d F Y')) }}
        </span>

        <div class="d-flex align-items-center ms-auto">
            <span class="me-3 text-light">
                👤 {{ Auth::user()->name ?? 'Utilisateur' }}
            </span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">
                    Déconnexion
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="dashboard">
    <div class="left-panel">
        <iframe src="{{ route('stats.index') }}" title="Statistiques"></iframe>
    </div>

    <div class="right-panel">
        <iframe src="{{ route('exercises.index') }}" title="Exercices"></iframe>
    </div>
</div>

</body>
</html>
