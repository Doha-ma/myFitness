@extends('layouts.app')

@section('title', 'Modifier Staff')

@section('sidebar')
    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition">📊 Dashboard</a>
    <a href="{{ route('admin.staff.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition">👥 Gestion Staff</a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="block w-full text-left px-4 py-3 rounded hover:bg-white/10 transition">🚪 Déconnexion</button>
    </form>
@endsection

@section('content')
<div class="card p-8 max-w-2xl">
    <h2 class="text-2xl font-bold mb-6">Modifier le staff</h2>
    
    <form method="POST" action="{{ route('admin.staff.update', $user) }}">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Nom complet</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" required>
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Rôle</label>
            <select name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                <option value="">Sélectionner un rôle</option>
                <option value="receptionist" {{ old('role', $user->role) == 'receptionist' ? 'selected' : '' }}>Réceptionniste</option>
                <option value="coach" {{ old('role', $user->role) == 'coach' ? 'selected' : '' }}>Coach</option>
            </select>
            @error('role')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
            <input type="password" name="password" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
        </div>

        <div class="flex gap-4">
            <button type="submit" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">
                ✅ Mettre à jour
            </button>
            <a href="{{ route('admin.staff.index') }}" class="bg-gray-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-600">
                ❌ Annuler
            </a>
        </div>
    </form>
</div>
@endsection
