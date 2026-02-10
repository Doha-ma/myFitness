@extends('layouts.admin')

@section('title', 'Ajouter un Coach')

@section('content')
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-4xl font-bold text-white mb-2 flex items-center gap-3">
                <i class="fas fa-user-tie text-blue-400"></i>
                Ajouter un Coach
            </h2>
            <p class="text-gray-300 text-sm">Créez un nouveau compte coach pour la salle de sport</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Dashboard
            </a>
            <a href="{{ route('admin.coaches.index') }}" class="btn btn-info">
                <i class="fas fa-list"></i>
                Liste des coachs
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

    <form method="POST" action="{{ route('admin.coaches.store') }}">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="name" class="form-label">
                        <i class="fas fa-user-tie me-2"></i>
                        Nom du Coach <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" 
                           placeholder="Entrez le nom complet du coach" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope me-2"></i>
                        Email <span class="text-danger">*</span>
                    </label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email') }}" 
                           placeholder="coach@example.com" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock me-2"></i>
                        Mot de passe <span class="text-danger">*</span>
                    </label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" 
                           placeholder="Entrez un mot de passe sécurisé" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">
                        <i class="fas fa-lock me-2"></i>
                        Confirmation du mot de passe <span class="text-danger">*</span>
                    </label>
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                           id="password_confirmation" name="password_confirmation" 
                           placeholder="Confirmez le mot de passe" required>
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="phone" class="form-label">
                        <i class="fas fa-phone me-2"></i>
                        Téléphone
                    </label>
                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                           id="phone" name="phone" value="{{ old('phone') }}" 
                           placeholder="+212 6XX-XXXXXX">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="address" class="form-label">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        Adresse
                    </label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror" 
                           id="address" name="address" value="{{ old('address') }}" 
                           placeholder="Entrez l'adresse complète">
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                <small><i class="fas fa-info-circle"></i> Les champs marqués d'un astérisque (*) sont obligatoires</small>
            </div>
            <div class="btn-group">
                <a href="{{ route('admin.coaches.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Créer le Coach
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
