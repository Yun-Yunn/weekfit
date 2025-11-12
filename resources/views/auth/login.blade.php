<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion | WeekFit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1593079831268-3381b0db4a77?auto=format&fit=crop&w=1470&q=80') center center / cover no-repeat;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        /* overlay sombre + dégradé */
        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(18, 52, 86, 0.9), rgba(0, 0, 0, 0.8));
            backdrop-filter: blur(6px);
            z-index: 0;
        }

        .login-box {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0, 255, 255, 0.2);
            width: 350px;
            text-align: center;
            color: white;
        }

        .login-box h2 {
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            color: #00ffcc;
            text-shadow: 0 0 10px rgba(0,255,255,0.6);
        }

        .form-control {
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .form-control:focus {
            border-color: #00ffcc;
            box-shadow: 0 0 10px #00ffcc;
            background: rgba(0, 0, 0, 0.6);
        }

        .btn-login {
            background: linear-gradient(135deg, #00ffcc, #007bff);
            border: none;
            color: black;
            font-weight: 600;
            letter-spacing: 0.5px;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 0 10px #00ffcc;
        }

        .btn-login:hover {
            transform: scale(1.05);
            box-shadow: 0 0 25px #00ffcc;
        }

        .brand {
            font-size: 2rem;
            font-weight: 900;
            color: #00ffcc;
            margin-bottom: 0.5rem;
            text-shadow: 0 0 20px #00ffff;
        }

        .subtitle {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="brand">WeekFit</div>
        <p class="subtitle">Ton coach virtuel, ta performance réelle</p>

        <h2>Connexion</h2>

        @if ($errors->any())
            <div class="alert alert-danger py-2">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3 text-start">
                <label class="form-label">Adresse e-mail</label>
                <input type="email" name="email" class="form-control" placeholder="ex: john@weekfit.fr" required autofocus>
            </div>

            <div class="mb-3 text-start">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <div class="form-check mb-3 text-start">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label for="remember" class="form-check-label">Se souvenir de moi</label>
            </div>

            <button type="submit" class="btn btn-login w-100 py-2">Se connecter</button>

            <div class="mt-3">
                <a href="{{ route('register') }}" class="text-light text-decoration-underline">Créer un compte</a>
            </div>
        </form>
    </div>
</body>
</html>
