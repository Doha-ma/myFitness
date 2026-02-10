@extends('layouts.admin')

@section('title', 'Modifier un Cours')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <h2 class="text-4xl font-bold text-white mb-2">Modifier un Cours</h2>
        <a href="{{ route('admin.classes.show', $classModel) }}" class="text-white hover:underline">← Retour aux détails</a>
    </div>
</div>

<div class="card p-6">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.classes.update', $classModel) }}" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Nom du cours -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                Nom du cours <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   id="name" 
                   name="name" 
                   value="{{ old('name', $classModel->name) }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900"
                   placeholder="Entrez le nom du cours"
                   required>
            @error('name')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-300 mb-2">
                Description
            </label>
            <textarea id="description" 
                      name="description" 
                      rows="4"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900"
                      placeholder="Décrivez le cours (optionnel)">{{ old('description', $classModel->description) }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Coach -->
            <div>
                <label for="coach_id" class="block text-sm font-medium text-gray-300 mb-2">
                    Coach <span class="text-red-500">*</span>
                </label>
                <select id="coach_id" 
                        name="coach_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900"
                        required>
                    <option value="">Sélectionnez un coach</option>
                    @foreach($coaches as $coach)
                        <option value="{{ $coach->id }}" {{ old('coach_id', $classModel->coach_id) == $coach->id ? 'selected' : '' }}>
                            {{ $coach->name }}
                        </option>
                    @endforeach
                </select>
                @error('coach_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Capacité -->
            <div>
                <label for="capacity" class="block text-sm font-medium text-gray-300 mb-2">
                    Capacité <span class="text-red-500">*</span>
                </label>
                <input type="number" 
                       id="capacity" 
                       name="capacity" 
                       value="{{ old('capacity', $classModel->capacity) }}"
                       min="1" 
                       max="100"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900"
                       placeholder="Nombre de participants"
                       required>
                @error('capacity')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Durée -->
            <div>
                <label for="duration" class="block text-sm font-medium text-gray-300 mb-2">
                    Durée (minutes) <span class="text-red-500">*</span>
                </label>
                <input type="number" 
                       id="duration" 
                       name="duration" 
                       value="{{ old('duration', $classModel->duration) }}"
                       min="15" 
                       max="480"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900"
                       placeholder="Durée en minutes"
                       required>
                @error('duration')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Statut -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-300 mb-2">
                    Statut <span class="text-red-500">*</span>
                </label>
                <select id="status" 
                        name="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900"
                        required>
                    <option value="pending" {{ old('status', $classModel->status) == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Approuvé</option>
                    <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rejeté</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Boutons -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.classes.show', $classModel) }}" class="px-6 py-2 border border-gray-300 text-gray-300 rounded-lg hover:bg-gray-700 transition">
                Annuler
            </a>
            <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg hover:shadow-lg transition">
                <i class="fas fa-save me-2"></i> Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection
