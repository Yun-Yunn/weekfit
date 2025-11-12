<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Dashboard WeekFit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* ====== Thème global ====== */
        body {
            background: linear-gradient(135deg, #0a192f, #112b53);
            color: #d9e6ff;
            height: 100vh;
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
        }

        /* ====== Navbar ====== */
        .navbar {
            background: rgba(17, 44, 90, 0.95);
            backdrop-filter: blur(6px);
            border-bottom: 1px solid rgba(0, 255, 255, 0.1);
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.15);
            height: 60px;
        }

        .navbar-brand {
            font-weight: 700;
            color: #00ffe1 !important;
            text-shadow: 0 0 8px rgba(0, 255, 255, 0.6);
        }

        .date {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            font-weight: 500;
            color: #cfe3ff;
            text-shadow: 0 0 6px rgba(0, 255, 255, 0.3);
        }

        /* ====== User info ====== */
        .user-name {
            font-weight: 500;
            color: #7ed9ff;
        }

        .btn-outline-light {
            border-color: #00ffe1;
            color: #00ffe1;
            transition: 0.3s ease;
        }

        .btn-outline-light:hover {
            background: linear-gradient(90deg, #00ffe1, #007bff);
            color: #021626;
            box-shadow: 0 0 12px #00ffe1;
        }

        /* ====== Dashboard Layout ====== */
        .dashboard {
            display: flex;
            height: calc(100vh - 60px);
            gap: 1rem;
            padding: 1rem;
            background: radial-gradient(circle at 20% 20%, #0d1a30, #091121 60%);
        }

        .left-panel,
        .right-panel {
            border-radius: 16px;
            padding: 1rem;
            background: rgba(12, 25, 45, 0.95);
            box-shadow: 0 0 25px rgba(0, 255, 255, 0.05);
            border: 1px solid rgba(0, 255, 255, 0.05);
        }

        .left-panel {
            flex: 1.1;
            display: flex;
            flex-direction: column;
        }

        .right-panel {
            flex: 2.5;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            position: relative;
        }

        /* ====== Iframes ====== */
        iframe {
            width: 100%;
            height: 100%;
            border: none;
            border-radius: 12px;
            background: rgba(20, 35, 65, 0.6);
        }

        iframe.fading {
            opacity: 0.5;
            transition: opacity 0.3s ease;
        }

        /* ====== Accents lumineux ====== */
        :root {
            --fit-cyan: #00ffe1;
            --fit-blue: #398af5;
            --fit-green: #00ff8c;
            --fit-dark: #0b1628;
        }

        /* ====== Animations ====== */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dashboard {
            animation: fadeUp 0.6s ease;
        }

        /* ====== Glow borders ====== */
        .left-panel::before,
        .right-panel::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 16px;
            padding: 1px;
            background: linear-gradient(135deg, rgba(0, 255, 255, 0.2), rgba(0, 255, 136, 0.05));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
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
                <span class="me-3 user-name">
                    👤 {{ Auth::user()->name ?? 'Athlète' }}
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
        <div class="left-panel position-relative">
            <iframe src="{{ route('stats.index') }}" title="Statistiques"></iframe>
        </div>

        <div class="right-panel position-relative">
            <iframe id="exerciseFrame" src="{{ route('exercises.index') }}" title="Exercices" scrolling="no"></iframe>
        </div>
    </div>

    <script>
        window.addEventListener("message", function(event) {
            if (event.data.type === "resize" && event.data.height) {
                const iframe = document.getElementById("exerciseFrame");
                const panel = iframe.parentElement;
                const maxHeight = panel.clientHeight - 40;

                iframe.classList.add("fading");

                setTimeout(() => {
                    const ratio = Math.min(1, maxHeight / event.data.height);
                    iframe.style.transform = `scale(${ratio})`;
                    iframe.style.height = event.data.height + "px";
                    iframe.classList.remove("fading");
                }, 150);
            }
        });
    </script>

</body>

</html>
