<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue - MyFitness</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --gym-primary: #ff6b35;
            --gym-secondary: #004e89;
            --gym-dark: #1a1a2e;
            --gym-light: #f8fafc;
        }

        body {
            min-height: 100vh;
            background-image: url('{{ asset('WelcomePage.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background: linear-gradient(120deg, rgba(26, 26, 46, 0.82), rgba(0, 78, 137, 0.55));
            z-index: 0;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .cta-btn {
            background: var(--gym-primary);
            transition: all 0.25s ease;
        }

        .cta-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 107, 53, 0.35);
            background: #ff8155;
        }
    </style>
</head>
<body class="flex items-center justify-center px-6">
    <main class="hero-content text-center max-w-2xl">
        <h1 class="text-4xl md:text-6xl font-black text-white tracking-wide">
            MY FITNESS
        </h1>
        <p class="mt-4 text-lg md:text-xl text-slate-100">
            Bienvenue dans votre espace de gestion sportive.
        </p>
        <a href="{{ route('login') }}"
           class="cta-btn inline-block mt-8 px-8 py-3 text-white font-bold rounded-xl text-lg">
            Se connecter
        </a>
    </main>
</body>
</html>

