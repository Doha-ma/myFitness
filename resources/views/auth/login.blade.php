<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gym Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --gym-primary: #FF6B35;
            --gym-secondary: #004E89;
            --gym-dark: #1A1A2E;
            --gym-accent: #F7931E;
            --gym-light: #FFE5D9;
        }
        
        body {
            background-image: url('{{ asset("posture_myFitness.png") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            position: relative;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(26, 26, 46, 0.85) 0%, rgba(0, 78, 137, 0.75) 100%);
            z-index: 0;
        }
        
        .login-container {
            position: relative;
            z-index: 1;
        }
        
        .btn-primary {
            background: var(--gym-primary);
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background: #ff8555;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 107, 53, 0.4);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        input:focus {
            border-color: var(--gym-primary);
            box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">
    <div class="login-container w-full max-w-md">
        <div class="glass-effect p-8 rounded-2xl shadow-2xl">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold mb-2" style="color: var(--gym-primary);"> GYM MANAGER</h1>
                <p class="text-gray-700 font-medium">Connectez-vous à votre compte</p>
            </div>

            @if($errors->any())
                <div class="bg-red-50 border-2 border-red-300 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <strong> Erreur :</strong> 
                    <ul class="list-disc list-inside mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-6">
                    <label class="block text-gray-800 font-semibold mb-2"> Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" 
                           class="w-full px-4 py-3 border-2 rounded-lg focus:outline-none transition @error('email') border-red-500 @else border-gray-300 @enderror" 
                           placeholder="votre@email.com"
                           required autofocus>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-800 font-semibold mb-2"> Mot de passe</label>
                    <input type="password" name="password" 
                           class="w-full px-4 py-3 border-2 rounded-lg focus:outline-none transition @error('password') border-red-500 @else border-gray-300 @enderror" 
                           placeholder="••••••••"
                           required>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="remember" class="mr-2 w-4 h-4 accent-orange-500">
                        <span class="text-sm text-gray-700">Se souvenir de moi</span>
                    </label>
                </div>

                <button type="submit" 
                        class="btn-primary w-full text-white py-3 rounded-lg font-bold text-lg shadow-lg">
                     Se connecter
                </button>
            </form>

           
        </div>
    </div>
</body>
</html>
