@extends('layouts.app')

@section('title', 'Enregistrer un paiement')

@section('sidebar')
    <a href="{{ route('receptionist.dashboard') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition"><i class="fas fa-tachometer-alt me-3"></i><span>Tableau de bord</span></a>
    <a href="{{ route('receptionist.members.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition"><i class="fas fa-users me-3"></i><span>Membres</span></a>
    <a href="{{ route('receptionist.payments.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition bg-white/10"><i class="fas fa-money-bill-wave me-3"></i><span>Paiements</span></a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="block w-full text-left px-4 py-3 rounded hover:bg-white/10 transition mt-4"><i class="fas fa-sign-out-alt me-3"></i><span>Deconnexion</span></button>
    </form>
@endsection

@section('content')
<div class="mb-6">
    <h2 class="text-3xl font-bold text-white mb-2">Enregistrer un paiement</h2>
    <p class="text-gray-300">Ajoutez un paiement pour un membre</p>
</div>

<div class="card p-8 max-w-2xl">
    @if($errors->any())
        <div class="bg-red-50 border-2 border-red-300 text-red-700 px-4 py-3 rounded-lg mb-6">
            <strong>Erreurs :</strong>
            <ul class="list-disc list-inside mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('receptionist.payments.store') }}">
        @csrf

        <div class="mb-6">
            <label class="block text-gray-800 font-semibold mb-2">Membre</label>
            <select name="member_id" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg" required>
                <option value="">Selectionner un membre</option>
                @foreach($members as $member)
                    <option value="{{ $member->id }}" {{ (string) old('member_id', $selectedMemberId ?? '') === (string) $member->id ? 'selected' : '' }}>
                        {{ $member->full_name }} - {{ $member->email }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-gray-800 font-semibold mb-2">Type d'abonnement</label>
            <select name="subscription_type_id" id="subscription_type_id" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg">
                <option value="">Selectionner un type d'abonnement</option>
                @foreach($subscriptionTypes as $subscriptionType)
                    <option
                        value="{{ $subscriptionType->id }}"
                        data-price="{{ $subscriptionType->final_price }}"
                        {{ old('subscription_type_id') == $subscriptionType->id ? 'selected' : '' }}
                    >
                        {{ $subscriptionType->name }} - {{ $subscriptionType->formatted_price }} ({{ $subscriptionType->duration_days }} jours)
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-800 font-semibold mb-2">Montant (DH)</label>
                <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount') }}" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg" placeholder="0.00" readonly>
                <small class="text-gray-600">Le montant est calcule automatiquement avec le type d'abonnement.</small>
            </div>
            <div>
                <label class="block text-gray-800 font-semibold mb-2">Date</label>
                <input type="date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg" required>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-gray-800 font-semibold mb-2">Methode de paiement</label>
            <select name="method" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg" required>
                <option value="cash" {{ old('method') == 'cash' ? 'selected' : '' }}>Especes</option>
                <option value="card" {{ old('method') == 'card' ? 'selected' : '' }}>Carte</option>
                <option value="transfer" {{ old('method') == 'transfer' ? 'selected' : '' }}>Virement</option>
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-gray-800 font-semibold mb-2">Notes (optionnel)</label>
            <textarea name="notes" rows="3" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg" placeholder="Informations complementaires">{{ old('notes') }}</textarea>
        </div>

        <div class="flex gap-4 pt-4">
            <button type="submit" class="btn-primary text-white px-8 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition">
                Enregistrer le paiement
            </button>
            <a href="{{ route('receptionist.payments.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-8 py-3 rounded-lg font-semibold transition">
                Annuler
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const subscriptionSelect = document.getElementById('subscription_type_id');
    const amountInput = document.getElementById('amount');

    function updateAmount() {
        const selectedOption = subscriptionSelect.options[subscriptionSelect.selectedIndex];
        if (selectedOption && selectedOption.value) {
            amountInput.value = selectedOption.getAttribute('data-price') || '';
        } else {
            amountInput.value = '';
        }
    }

    subscriptionSelect.addEventListener('change', updateAmount);
    updateAmount();
});
</script>
@endsection
