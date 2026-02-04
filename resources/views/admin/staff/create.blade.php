@extends('layouts.app')

@section('title', 'Ajouter Staff')

@section('sidebar')
    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition"> Dashboard</a>
    <a href="{{ route('admin.staff.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition bg-white/10"> Gestion Staff</a>
    <a href="{{ route('admin.subscription-types.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition"> Types d'Abonnement</a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="block w-full text-left px-4 py-3 rounded hover:bg-white/10 transition mt-4"> Déconnexion</button>
    </form>
@endsection

@section('content')
<div class="mb-6">
    <h2 class="text-3xl font-bold text-white mb-2">Ajouter un nouveau staff</h2>
    <p class="text-gray-300">Créez un nouveau compte pour un réceptionniste ou un coach</p>
</div>

<div class="card p-8 max-w-2xl">
    @if($errors->any())
        <div class="bg-red-50 border-2 border-red-300 text-red-700 px-4 py-3 rounded-lg mb-6">
            <strong> Erreurs :</strong>
            <ul class="list-disc list-inside mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form method="POST" action="{{ route('admin.staff.store') }}">
        @csrf
        
        <div class="mb-6">
            <label class="block text-gray-800 font-semibold mb-2"> Nom complet</label>
            <input type="text" name="name" value="{{ old('name') }}" 
                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none transition" 
                   placeholder="Ex: Jean Dupont"
                   required>
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-800 font-semibold mb-2"> Email</label>
            <input type="email" name="email" value="{{ old('email') }}" 
                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none transition" 
                   placeholder="exemple@gym.com"
                   required>
            <p class="mt-1 text-xs text-gray-500">Cet email sera utilisé pour la connexion</p>
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-800 font-semibold mb-2"> Rôle</label>
            <select name="role" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none transition" required>
                <option value="">Sélectionner un rôle</option>
                <option value="receptionist" {{ old('role') == 'receptionist' ? 'selected' : '' }}> Réceptionniste</option>
                <option value="coach" {{ old('role') == 'coach' ? 'selected' : '' }}> Coach</option>
            </select>
            @error('role')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-800 font-semibold mb-2"> Mot de passe</label>
            <input type="password" name="password" 
                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none transition" 
                   placeholder="Minimum 8 caractères"
                   required>
            <p class="mt-1 text-xs text-gray-500">Le mot de passe sera utilisé pour la connexion</p>
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-800 font-semibold mb-2"> Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" 
                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none transition" 
                   placeholder="Répétez le mot de passe"
                   required>
        </div>

        <div class="flex gap-4 pt-4">
            <button type="submit" class="btn-primary text-white px-8 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition">
                 Créer le Staff
            </button>
            <a href="{{ route('admin.staff.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-8 py-3 rounded-lg font-semibold transition">
                 Annuler
            </a>
        </div>
    </form>
</div>
@endsection
