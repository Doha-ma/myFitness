@extends('layouts.app')

@section('title', 'Modifier Cours')

@section('sidebar')
    <a href="{{ route('coach.dashboard') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition">📊 Dashboard</a>
    <a href="{{ route('coach.classes.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition">🏋️ Mes Cours</a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="block w-full text-left px-4 py-3 rounded hover:bg-white/10 transition">🚪 Déconnexion</button>
    </form>
@endsection

@section('content')
<div class="card p-8 max-w-2xl">
    <h2 class="text-2xl font-bold mb-6">Modifier le cours</h2>
    
    <form method="POST" action="{{ route('coach.classes.update', $class) }}">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Nom du cours</label>
            <input type="text" name="name" value="{{ old('name', $class->name) }}" 
                   placeholder="Ex: Yoga, CrossFit, Cardio..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Description</label>
            <textarea name="description" rows="4" 
                      placeholder="Décrivez le cours, niveau requis, objectifs..."
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">{{ old('description', $class->description) }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Capacité (personnes)</label>
                <input type="number" name="capacity" value="{{ old('capacity', $class->capacity) }}" min="1"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" required>
            </div>
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Durée (minutes)</label>
                <input type="number" name="duration" value="{{ old('duration', $class->duration) }}" min="15"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" required>
            </div>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">
                ✅ Enregistrer
            </button>
            <a href="{{ route('coach.classes.show', $class) }}" class="bg-gray-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-600">
                ❌ Annuler
            </a>
        </div>
    </form>
</div>
@endsection
