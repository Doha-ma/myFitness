<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reinitialiser le mot de passe - MyFitness</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-6">
        <h1 class="text-2xl font-bold text-slate-800 mb-5">Changer votre mot de passe</h1>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email', $request->email) }}"
                required
                autofocus
                autocomplete="username"
                class="w-full rounded-md border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400"
            >
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror

            <label for="password" class="mt-4 block text-sm font-medium text-slate-700 mb-1">Nouveau mot de passe</label>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                class="w-full rounded-md border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400"
            >
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror

            <label for="password_confirmation" class="mt-4 block text-sm font-medium text-slate-700 mb-1">Confirmer le mot de passe</label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                class="w-full rounded-md border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400"
            >
            @error('password_confirmation')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror

            <button
                type="submit"
                class="mt-5 w-full rounded-md bg-orange-500 px-4 py-2 font-semibold text-white hover:bg-orange-600"
            >
                Reinitialiser le mot de passe
            </button>
        </form>
    </div>
</body>
</html>

