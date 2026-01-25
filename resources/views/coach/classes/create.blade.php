@extends('layouts.app')

@section('title', 'Créer Cours')

@section('sidebar')
    <a href="{{ route('coach.dashboard') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition">📊 Dashboard</a>
    <a href="{{ route('coach.classes.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition bg-white/10">🏋️ Mes Cours</a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="block w-full text-left px-4 py-3 rounded hover:bg-white/10 transition mt-4">🚪 Déconnexion</button>
    </form>
@endsection

@section('content')
<div class="mb-6">
    <h2 class="text-3xl font-bold text-white mb-2">Créer un nouveau cours</h2>
    <p class="text-gray-300">Ajoutez un nouveau cours à votre programme</p>
</div>

<div class="card p-8 max-w-2xl">
    @if($errors->any())
        <div class="bg-red-50 border-2 border-red-300 text-red-700 px-4 py-3 rounded-lg mb-6">
            <strong>⚠️ Erreurs :</strong>
            <ul class="list-disc list-inside mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form method="POST" action="{{ route('coach.classes.store') }}">
        @csrf
        
        <div class="mb-6">
            <label class="block text-gray-800 font-semibold mb-2">🏋️ Nom du cours</label>
            <input type="text" name="name" value="{{ old('name') }}" 
                   placeholder="Ex: Yoga, CrossFit, Cardio, Musculation..."
                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none transition" 
                   required>
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-800 font-semibold mb-2">📝 Description</label>
            <textarea name="description" rows="4" 
                      placeholder="Décrivez le cours, niveau requis, objectifs, équipements nécessaires..."
                      class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none transition">{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-800 font-semibold mb-2">👥 Capacité (personnes)</label>
                <input type="number" name="capacity" value="{{ old('capacity', 20) }}" min="1"
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none transition" 
                       required>
                <p class="mt-1 text-xs text-gray-500">Nombre maximum de participants</p>
                @error('capacity')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-gray-800 font-semibold mb-2">⏱️ Durée (minutes)</label>
                <input type="number" name="duration" value="{{ old('duration', 60) }}" min="15"
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none transition" 
                       required>
                <p class="mt-1 text-xs text-gray-500">Durée d'une session</p>
                @error('duration')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex gap-4 pt-4">
            <button type="submit" class="btn-primary text-white px-8 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition">
                ✅ Créer le Cours
            </button>
            <a href="{{ route('coach.classes.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-8 py-3 rounded-lg font-semibold transition">
                ❌ Annuler
            </a>
        </div>
    </form>
</div>
@endsection
