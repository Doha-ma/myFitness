@extends('layouts.app')

@section('title', 'Modifier un Type d\'Abonnement')

@section('sidebar')
    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition"> Dashboard</a>
    <a href="{{ route('admin.staff.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition"> Gestion Staff</a>
    <a href="{{ route('admin.subscription-types.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition bg-white/10"> Types d'Abonnement</a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="block w-full text-left px-4 py-3 rounded hover:bg-white/10 transition mt-4"> Déconnexion</button>
    </form>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Modifier le type d'abonnement</h1>
    <a href="{{ route('admin.subscription-types.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('admin.subscription-types.update', $subscriptionType) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $subscriptionType->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="duration_days" class="form-label">Durée (jours) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('duration_days') is-invalid @enderror" 
                                           id="duration_days" name="duration_days" value="{{ old('duration_days', $subscriptionType->duration_days) }}" min="1" required>
                                    @error('duration_days')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Ex: 30 pour mensuel, 90 pour trimestriel, 365 pour annuel</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $subscriptionType->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="base_price" class="form-label">Prix de base (DH) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('base_price') is-invalid @enderror" 
                                           id="base_price" name="base_price" value="{{ old('base_price', $subscriptionType->base_price) }}" 
                                           step="0.01" min="0" required>
                                    @error('base_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="discount_type" class="form-label">Type de réduction <span class="text-danger">*</span></label>
                                    <select class="form-select @error('discount_type') is-invalid @enderror" 
                                            id="discount_type" name="discount_type" required>
                                        <option value="percentage" {{ old('discount_type', $subscriptionType->discount_type) == 'percentage' ? 'selected' : '' }}>Pourcentage (%)</option>
                                        <option value="fixed" {{ old('discount_type', $subscriptionType->discount_type) == 'fixed' ? 'selected' : '' }}>Montant fixe (DH)</option>
                                    </select>
                                    @error('discount_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="discount_value" class="form-label">Valeur de réduction <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('discount_value') is-invalid @enderror" 
                                           id="discount_value" name="discount_value" value="{{ old('discount_value', $subscriptionType->discount_value) }}" 
                                           step="0.01" min="0" required>
                                    @error('discount_value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                       value="1" {{ old('is_active', $subscriptionType->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Actif
                                </label>
                            </div>
                        </div>

                        <!-- Price Preview -->
                        <div class="alert alert-info">
                            <h6><i class="fas fa-calculator"></i> Aperçu du prix final</h6>
                            <div id="pricePreview">
                                <span class="fw-bold">Prix final: </span>
                                <span id="finalPrice" class="fw-bold text-success">{{ $subscriptionType->formatted_price }}</span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.subscription-types.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
@endsection

@push('scripts')
<script>
    function calculateFinalPrice() {
        const basePrice = parseFloat(document.getElementById('base_price').value) || 0;
        const discountType = document.getElementById('discount_type').value;
        const discountValue = parseFloat(document.getElementById('discount_value').value) || 0;
        
        let finalPrice = basePrice;
        
        if (discountType === 'percentage') {
            finalPrice = basePrice * (1 - (discountValue / 100));
        } else {
            finalPrice = Math.max(0, basePrice - discountValue);
        }
        
        document.getElementById('finalPrice').textContent = finalPrice.toFixed(2).replace('.', ',') + ' DH';
    }
    
    document.getElementById('base_price').addEventListener('input', calculateFinalPrice);
    document.getElementById('discount_type').addEventListener('change', calculateFinalPrice);
    document.getElementById('discount_value').addEventListener('input', calculateFinalPrice);
    
    // Initial calculation
    calculateFinalPrice();
</script>
@endpush
