@extends('layouts.admin')

@section('title', 'Modifier un Réceptionniste')

@section('content')
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-4xl font-bold text-white mb-2 flex items-center gap-3">
                <i class="fas fa-user-edit text-yellow-400"></i>
                Modifier un Réceptionniste
            </h2>
            <p class="text-gray-300 text-sm">Modifiez les informations du compte réceptionniste</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Dashboard
            </a>
            <a href="{{ route('admin.receptionists.index') }}" class="btn btn-info">
                <i class="fas fa-list"></i>
                Liste des réceptionnistes
            </a>
        </div>
    </div>
</div>

<div class="card p-6">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-3"></i>
                <div>
                    <strong>Succès!</strong> {{ session('success') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-3"></i>
                <div>
                    <strong>Erreur!</strong> {{ session('error') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.receptionists.update', $receptionist) }}">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="mb-4">
                    <label for="name" class="form-label">
                        <i class="fas fa-user mr-2"></i>
                        Nom complet
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           class="form-control" 
                           value="{{ old('name', $receptionist->name) }}"
                           placeholder="Entrez le nom complet"
                           required>
                    @error('name')
                        <div class="text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            
            <div>
                <div class="mb-4">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope mr-2"></i>
                        Adresse email
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-control" 
                           value="{{ old('email', $receptionist->email) }}"
                           placeholder="receptionniste@example.com"
                           required>
                    @error('email')
                        <div class="text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock mr-2"></i>
                        Nouveau mot de passe <span class="text-gray-500">(laisser vide pour ne pas changer)</span>
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control" 
                           placeholder="Entrez le nouveau mot de passe">
                    @error('password')
                        <div class="text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">
                        <i class="fas fa-lock mr-2"></i>
                        Confirmer le mot de passe
                    </label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           class="form-control" 
                           placeholder="Confirmez le nouveau mot de passe">
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between mt-6">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-2"></i>
                Mettre à jour
            </button>
            
            <a href="{{ route('admin.receptionists.index') }}" class="btn btn-secondary">
                <i class="fas fa-times mr-2"></i>
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
