<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Dashboard WeekFit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #398af5;
            color: #fff;
            overflow: hidden;
            height: 100vh;
        }

        .navbar {
            background-color: #1244b1;
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

        .left-panel,
        .right-panel {
            background-color: #2b2b2b;
            border-radius: 10px;
            padding: 1rem;
            overflow: hidden;
        }

        .left-panel {
            flex: 1.2;
            display: flex;
            flex-direction: column;
        }

        .right-panel {
            flex: 2.5;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            /* ✅ ancrage haut */
            position: relative;
            overflow: hidden;
        }

iframe {
    width: 100%;
    height: 100%;
    border: none;
    border-radius: 10px;
    background: transparent;
    overflow: hidden;
}


        iframe.fading {
            opacity: 0.6;
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
            <iframe id="exerciseFrame" src="{{ route('exercises.index') }}" title="Exercices" scrolling="no">
            </iframe>
        </div>
    </div>

    <script>
        // ⚙️ Ajustement dynamique, sans scroll ni vide, toujours collé en haut
        window.addEventListener("message", function(event) {
            if (event.data.type === "resize" && event.data.height) {
                const iframe = document.getElementById("exerciseFrame");
                const panel = iframe.parentElement;
                const maxHeight = panel.clientHeight - 40;

                iframe.classList.add("fading");

                setTimeout(() => {
                    const ratio = Math.min(1, maxHeight / event.data.height);
                    iframe.style.transform = `scale(${ratio})`; // ✅ pas de translate, juste scale
                    iframe.style.height = event.data.height + "px";
                    iframe.classList.remove("fading");
                }, 150);
            }
        });
    </script>

</body>

</html>
