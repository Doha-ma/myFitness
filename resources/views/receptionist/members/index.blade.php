@extends('layouts.app')

@section('title', 'Gestion des membres')

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
<div class="mb-8">
    <div class="flex justify-between items-center">
        <h2 class="text-4xl font-bold text-white">Gestion des membres</h2>
        <a href="{{ route('receptionist.members.create') }}" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">
            Ajouter un membre
        </a>
    </div>
</div>

<div class="card p-6">
    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
        <form method="GET" action="{{ route('receptionist.members.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Classe</label>
                    <select name="class_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Toutes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }} @if($class->coach) - {{ $class->coach->name }} @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Type d'abonnement</label>
                    <select name="subscription_type_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Tous</option>
                        @foreach($subscriptionTypes as $subscriptionType)
                            <option value="{{ $subscriptionType->id }}" {{ request('subscription_type_id') == $subscriptionType->id ? 'selected' : '' }}>
                                {{ $subscriptionType->name }} - {{ $subscriptionType->formatted_price }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Statut abonnement</label>
                    <select name="subscription_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Tous</option>
                        <option value="active" {{ request('subscription_status') === 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="expired" {{ request('subscription_status') === 'expired' ? 'selected' : '' }}>Expire</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold transition">
                        Filtrer
                    </button>
                    <a href="{{ route('receptionist.members.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    @if($members->isEmpty())
        <p class="text-gray-500 text-center py-8">Aucun membre trouve.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-3 px-4">Nom</th>
                        <th class="text-left py-3 px-4">Email</th>
                        <th class="text-left py-3 px-4">Telephone</th>
                        <th class="text-left py-3 px-4">Type d'abonnement</th>
                        <th class="text-left py-3 px-4">Date de fin</th>
                        <th class="text-left py-3 px-4">Statut</th>
                        <th class="text-left py-3 px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $member)
                        @php
                            $subscriptionType = $member->latestSubscriptionPayment?->subscriptionType;
                            $endDate = $member->resolved_subscription_end_date;
                        @endphp
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4 font-semibold">{{ $member->full_name }}</td>
                            <td class="py-3 px-4">{{ $member->email }}</td>
                            <td class="py-3 px-4">{{ $member->phone ?: '-' }}</td>
                            <td class="py-3 px-4">
                                @if($subscriptionType)
                                    <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                                        {{ $subscriptionType->name }}
                                    </span>
                                @else
                                    <span class="text-gray-500 text-sm">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">{{ $endDate ? $endDate->format('d/m/Y') : '-' }}</td>
                            <td class="py-3 px-4">
                                @if($member->subscription_state === 'active')
                                    <span class="px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">Actif</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-sm bg-red-100 text-red-800">Expire</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('receptionist.members.edit', $member) }}" class="text-blue-500 hover:text-blue-700">Fiche membre</a>
                                    <form method="POST" action="{{ route('receptionist.members.destroy', $member) }}" class="inline" onsubmit="return confirm('Confirmer la suppression de ce membre ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $members->links() }}
        </div>
    @endif
</div>
@endsection
