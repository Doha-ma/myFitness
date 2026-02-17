@extends('layouts.app')

@section('title', 'Fiche membre')

@section('sidebar')
    <a href="{{ route('receptionist.dashboard') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition"><i class="fas fa-tachometer-alt me-3"></i><span>Tableau de bord</span></a>
    <a href="{{ route('receptionist.members.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition bg-white/10"><i class="fas fa-users me-3"></i><span>Membres</span></a>
    <a href="{{ route('receptionist.payments.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition"><i class="fas fa-money-bill-wave me-3"></i><span>Paiements</span></a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="block w-full text-left px-4 py-3 rounded hover:bg-white/10 transition"><i class="fas fa-sign-out-alt me-3"></i><span>Deconnexion</span></button>
    </form>
@endsection

@section('content')
<div class="card p-8 max-w-5xl">
    <h2 class="text-2xl font-bold mb-6">Fiche membre</h2>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg">
            <p class="font-semibold">Veuillez corriger les erreurs suivantes :</p>
            <ul class="list-disc list-inside mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div>
            <form method="POST" action="{{ route('receptionist.members.update', $member) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Prenom</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $member->first_name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Nom</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $member->last_name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $member->email) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Telephone</label>
                        <input type="text" name="phone" value="{{ old('phone', $member->phone) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Adresse</label>
                    <textarea name="address" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('address', $member->address) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Date d'inscription</label>
                        <input type="date" name="join_date" value="{{ old('join_date', $member->join_date->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Statut compte</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                            <option value="active" {{ old('status', $member->status) == 'active' ? 'selected' : '' }}>Actif</option>
                            <option value="inactive" {{ old('status', $member->status) == 'inactive' ? 'selected' : '' }}>Inactif</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Date de fin d'abonnement</label>
                    <input type="date" name="subscription_end_date" value="{{ old('subscription_end_date', optional($member->subscription_end_date)->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Cours choisis (abonnement)</label>
                    <p class="text-sm text-gray-600 mb-3">Selectionnez les cours a associer a ce membre.</p>

                    @php
                        $selectedClasses = collect(old('classes', $member->classes->pluck('id')->all()))
                            ->map(fn ($id) => (string) $id)
                            ->all();
                    @endphp

                    @if(isset($classes) && $classes->isEmpty())
                        <div class="bg-yellow-50 border border-yellow-300 text-yellow-800 px-4 py-3 rounded-lg">
                            Aucun cours approuve disponible pour le moment.
                        </div>
                    @else
                        <div class="border border-gray-300 rounded-lg p-3 max-h-64 overflow-y-auto space-y-2">
                            @foreach($classes as $class)
                                <label class="flex items-start gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                                    <input
                                        type="checkbox"
                                        name="classes[]"
                                        value="{{ $class->id }}"
                                        class="mt-1"
                                        {{ in_array((string) $class->id, $selectedClasses, true) ? 'checked' : '' }}
                                    >
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $class->name }}</p>
                                        <p class="text-xs text-gray-600">
                                            Coach: {{ $class->coach->name ?? 'N/A' }} | Capacite: {{ $class->capacity }} | Duree: {{ $class->duration }} min
                                        </p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @endif

                    @error('classes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('classes.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">
                        Enregistrer les modifications
                    </button>
                    <a href="{{ route('receptionist.members.index') }}" class="bg-gray-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-600">
                        Retour
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
            <h3 class="text-xl font-bold mb-4">Actions abonnement</h3>

            <div class="mb-4">
                @if($member->subscription_state === 'active')
                    <span class="inline-flex px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">Abonnement actif</span>
                @else
                    <span class="inline-flex px-3 py-1 rounded-full text-sm bg-red-100 text-red-800">Abonnement expire</span>
                @endif
                <p class="text-sm text-gray-600 mt-2">
                    Type actuel :
                    <span class="font-semibold">{{ $member->latestSubscriptionPayment?->subscriptionType?->name ?? 'Non defini' }}</span>
                </p>
            </div>

            <div class="space-y-4">
                <form method="POST" action="{{ route('receptionist.members.subscription.update', $member) }}" class="p-4 bg-white rounded-lg border border-gray-200">
                    @csrf
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Changer le type d'abonnement</label>
                    <select name="subscription_type_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg mb-3" required>
                        <option value="">Selectionner un abonnement</option>
                        @foreach($subscriptionTypes as $subscriptionType)
                            <option
                                value="{{ $subscriptionType->id }}"
                                {{ (string) old('subscription_type_id', optional($member->latestSubscriptionPayment?->subscriptionType)->id) === (string) $subscriptionType->id ? 'selected' : '' }}
                            >
                                {{ $subscriptionType->name }} - {{ $subscriptionType->formatted_price }} ({{ $subscriptionType->duration_days }} jours)
                            </option>
                        @endforeach
                    </select>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-3">
                        <input
                            type="date"
                            name="payment_date"
                            value="{{ old('payment_date', now()->format('Y-m-d')) }}"
                            class="px-3 py-2 border border-gray-300 rounded-lg"
                            required
                        >
                        <select name="method" class="px-3 py-2 border border-gray-300 rounded-lg" required>
                            <option value="cash" {{ old('method', 'cash') === 'cash' ? 'selected' : '' }}>Especes</option>
                            <option value="card" {{ old('method') === 'card' ? 'selected' : '' }}>Carte</option>
                            <option value="transfer" {{ old('method') === 'transfer' ? 'selected' : '' }}>Virement</option>
                        </select>
                    </div>

                    <textarea
                        name="notes"
                        rows="2"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg mb-3"
                        placeholder="Notes (optionnel)"
                    >{{ old('notes') }}</textarea>

                    <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg font-semibold">
                        Mettre a jour l'abonnement
                    </button>
                </form>

                <form method="POST" action="{{ route('receptionist.members.renew', $member) }}" class="p-4 bg-white rounded-lg border border-gray-200">
                    @csrf
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Renouveler (jours)</label>
                    <div class="flex gap-2">
                        <input type="number" name="duration_days" min="1" max="365" value="30" class="w-28 px-3 py-2 border border-gray-300 rounded-lg">
                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-semibold">Renouveler</button>
                    </div>
                </form>

                <form method="POST" action="{{ route('receptionist.members.subscription-end-date', $member) }}" class="p-4 bg-white rounded-lg border border-gray-200">
                    @csrf
                    @method('PATCH')
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Modifier la date de fin</label>
                    <div class="flex gap-2">
                        <input type="date" name="subscription_end_date" required class="px-3 py-2 border border-gray-300 rounded-lg" value="{{ optional($member->subscription_end_date)->format('Y-m-d') }}">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold">Mettre a jour</button>
                    </div>
                </form>

                <form method="POST" action="{{ route('receptionist.members.mark-paid', $member) }}" class="p-4 bg-white rounded-lg border border-gray-200">
                    @csrf
                    <p class="text-sm text-gray-700 mb-3">Marquer le membre comme paye et reactiver l'abonnement.</p>
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-semibold">Marquer comme paye</button>
                </form>

                <a href="{{ route('receptionist.payments.create', ['member_id' => $member->id]) }}" class="inline-flex items-center text-orange-600 hover:text-orange-700 font-semibold">
                    Enregistrer un paiement pour ce membre
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
