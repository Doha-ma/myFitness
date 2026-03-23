@extends('layouts.admin')

@section('title', 'Ajouter un Type d\'Abonnement')

@section('content')
<div class="mb-8">
    <h2 class="text-4xl font-bold text-white mb-2">Types d'Abonnement</h2>
    <p class="text-gray-200">Créez un nouveau type d'abonnement pour vos membres</p>
</div>

<div class="flex justify-between items-center mb-6">
    <div>
        <h3 class="text-xl font-semibold text-white">Ajouter un type d'abonnement</h3>
        <p class="text-gray-300 text-sm mt-1">Configurez les détails du nouvel abonnement</p>
    </div>
    <a href="{{ route('admin.subscription-types.index') }}" class="btn btn-secondary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Retour
    </a>
</div>

            <div class="card">
    <div class="card-body">
        <form action="{{ route('admin.subscription-types.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label for="name" class="form-label flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Nom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" class="form-input @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="duration_days" class="form-label flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Durée (jours) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" class="form-input @error('duration_days') is-invalid @enderror" 
                           id="duration_days" name="duration_days" value="{{ old('duration_days', 30) }}" min="1" required>
                    @error('duration_days')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                    <div class="form-hint">Ex: 30 pour mensuel, 90 pour trimestriel, 365 pour annuel</div>
                </div>
            </div>

            <div class="form-group">
                <label for="description" class="form-label flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Description
                </label>
                <textarea class="form-textarea @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="form-group">
                    <label for="base_price" class="form-label flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Prix de base (DH) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" class="form-input @error('base_price') is-invalid @enderror" 
                           id="base_price" name="base_price" value="{{ old('base_price') }}" 
                           step="0.01" min="0" required>
                    @error('base_price')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="discount_type" class="form-label flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Type de réduction <span class="text-red-500">*</span>
                    </label>
                    <select class="form-select @error('discount_type') is-invalid @enderror" 
                            id="discount_type" name="discount_type" required>
                        <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Pourcentage (%)</option>
                        <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Montant fixe (DH)</option>
                    </select>
                    @error('discount_type')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="discount_value" class="form-label flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Valeur de réduction <span class="text-red-500">*</span>
                    </label>
                    <input type="number" class="form-input @error('discount_value') is-invalid @enderror" 
                           id="discount_value" name="discount_value" value="{{ old('discount_value', 0) }}" 
                           step="0.01" min="0" required>
                    @error('discount_value')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" id="is_active" name="is_active" 
                           value="1" {{ old('is_active', 1) ? 'checked' : '' }}
                           class="mr-3 w-4 h-4" style="accent-color: var(--gym-primary);">
                    <span class="text-white">Actif</span>
                </label>
            </div>

            <!-- Price Preview -->
            <div class="alert alert-info">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="font-semibold">Aperçu du prix final</span>
                </div>
                <div id="pricePreview">
                    <span class="font-medium">Prix final: </span>
                    <span id="finalPrice" class="font-bold text-success">0.00 DH</span>
                </div>
            </div>

            <div class="flex justify-between items-center mt-8">
                <a href="{{ route('admin.subscription-types.index') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Enregistrer
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

