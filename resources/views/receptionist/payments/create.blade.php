@extends('layouts.app')

@section('title', 'Enregistrer Paiement')

@section('sidebar')
    <a href="{{ route('receptionist.dashboard') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition">📊 Dashboard</a>
    <a href="{{ route('receptionist.members.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition">👥 Membres</a>
    <a href="{{ route('receptionist.payments.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition bg-white/10">💰 Paiements</a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="block w-full text-left px-4 py-3 rounded hover:bg-white/10 transition mt-4">🚪 Déconnexion</button>
    </form>
@endsection

@section('content')
<div class="mb-6">
    <h2 class="text-3xl font-bold text-white mb-2">Enregistrer un paiement</h2>
    <p class="text-gray-300">Enregistrez un nouveau paiement pour un membre</p>
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
    
    <form method="POST" action="{{ route('receptionist.payments.store') }}">
        @csrf
        
        <div class="mb-6">
            <label class="block text-gray-800 font-semibold mb-2">👤 Membre</label>
            <select name="member_id" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none transition" required>
                <option value="">Sélectionner un membre</option>
                @foreach($members as $member)
                    <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                        {{ $member->full_name }} - {{ $member->email }}
                    </option>
                @endforeach
            </select>
            @error('member_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-800 font-semibold mb-2">💰 Montant (€)</label>
                <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" 
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none transition" 
                       placeholder="0.00"
                       required>
                @error('amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-gray-800 font-semibold mb-2">📅 Date</label>
                <input type="date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" 
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none transition" 
                       required>
                @error('payment_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-gray-800 font-semibold mb-2">💳 Méthode de paiement</label>
            <select name="method" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none transition" required>
                <option value="cash" {{ old('method') == 'cash' ? 'selected' : '' }}>💵 Espèces</option>
                <option value="card" {{ old('method') == 'card' ? 'selected' : '' }}>💳 Carte</option>
                <option value="transfer" {{ old('method') == 'transfer' ? 'selected' : '' }}>🏦 Virement</option>
            </select>
            @error('method')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-800 font-semibold mb-2">📝 Notes (optionnel)</label>
            <textarea name="notes" rows="3" 
                      class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none transition"
                      placeholder="Notes supplémentaires sur le paiement...">{{ old('notes') }}</textarea>
            @error('notes')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-4 pt-4">
            <button type="submit" class="btn-primary text-white px-8 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition">
                💰 Enregistrer le Paiement
            </button>
            <a href="{{ route('receptionist.payments.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-8 py-3 rounded-lg font-semibold transition">
                ❌ Annuler
            </a>
        </div>
    </form>
</div>
@endsection
