<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublie - MyFitness</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-6">
        <h1 class="text-2xl font-bold text-slate-800 mb-3">Mot de passe oublie</h1>
        <p class="text-sm text-slate-600 mb-4">
            Entrez votre email (coach ou receptionniste) pour recevoir un lien de reinitialisation.
        </p>

        @if (session('status'))
            <div class="mb-4 rounded-md border border-green-200 bg-green-50 p-3 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                class="w-full rounded-md border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400"
            >
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror

            <button
                type="submit"
                class="mt-5 w-full rounded-md bg-orange-500 px-4 py-2 font-semibold text-white hover:bg-orange-600"
            >
                Envoyer le lien de reinitialisation
            </button>
        </form>

        <a href="{{ route('login') }}" class="mt-4 block text-center text-sm font-medium text-slate-600 hover:text-slate-800">
            Retour a la connexion
        </a>
    </div>
</body>
</html>

