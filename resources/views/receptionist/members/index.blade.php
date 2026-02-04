@extends('layouts.app')

@section('title', 'Gestion Membres')

@section('sidebar')
    <a href="{{ route('receptionist.dashboard') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition"> Dashboard</a>
    <a href="{{ route('receptionist.members.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition bg-white/10"> Membres</a>
    <a href="{{ route('receptionist.payments.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition"> Paiements</a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="block w-full text-left px-4 py-3 rounded hover:bg-white/10 transition"> Déconnexion</button>
    </form>
@endsection

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <h2 class="text-4xl font-bold text-white">Gestion des Membres</h2>
        <a href="{{ route('receptionist.members.create') }}" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">
             Ajouter un Membre
        </a>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
        {{ session('error') }}
    </div>
@endif

<div class="card p-6">
    <!-- Filters -->
    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
        <form method="GET" action="{{ route('receptionist.members.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Filtrer par classe</label>
                    <select name="class_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Toutes les classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }} @if($class->coach) - {{ $class->coach->name }} @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Filtrer par type d'abonnement</label>
                    <select name="subscription_type_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Tous les abonnements</option>
                        @foreach($subscriptionTypes as $subscriptionType)
                            <option value="{{ $subscriptionType->id }}" {{ request('subscription_type_id') == $subscriptionType->id ? 'selected' : '' }}>
                                {{ $subscriptionType->name }} - {{ $subscriptionType->formatted_price }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold transition">
                        <i class="fas fa-filter"></i> Filtrer
                    </button>
                </div>
            </div>
            
            @if(request()->hasAny(['class_id', 'subscription_type_id']))
                <div class="mt-4">
                    <a href="{{ route('receptionist.members.index') }}" class="text-blue-500 hover:text-blue-700">
                        <i class="fas fa-times"></i> Réinitialiser les filtres
                    </a>
                </div>
            @endif
        </form>
    </div>

    @if($members->isEmpty())
        <p class="text-gray-500 text-center py-8">Aucun membre trouvé</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-3 px-4">Nom</th>
                        <th class="text-left py-3 px-4">Email</th>
                        <th class="text-left py-3 px-4">Téléphone</th>
                        <th class="text-left py-3 px-4">Classes inscrites</th>
                        <th class="text-left py-3 px-4">Type d'abonnement</th>
                        <th class="text-left py-3 px-4">Date d'inscription</th>
                        <th class="text-left py-3 px-4">Statut</th>
                        <th class="text-left py-3 px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $member)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4 font-semibold">{{ $member->full_name }}</td>
                            <td class="py-3 px-4">{{ $member->email }}</td>
                            <td class="py-3 px-4">{{ $member->phone }}</td>
                            <td class="py-3 px-4">
                                @if($member->classes->isNotEmpty())
                                    <div class="space-y-1">
                                        @foreach($member->classes->take(2) as $class)
                                            <span class="inline-block px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                                {{ $class->name }}
                                            </span>
                                        @endforeach
                                        @if($member->classes->count() > 2)
                                            <span class="text-xs text-gray-500">+{{ $member->classes->count() - 2 }} autre(s)</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-500 text-sm">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @php
                                    $lastPayment = $member->payments()->with('subscriptionType')->latest()->first();
                                @endphp
                                @if($lastPayment && $lastPayment->subscriptionType)
                                    <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                                        {{ $lastPayment->subscriptionType->name }}
                                    </span>
                                @else
                                    <span class="text-gray-500 text-sm">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">{{ $member->join_date->format('d/m/Y') }}</td>
                            <td class="py-3 px-4">
                                <span class="px-3 py-1 rounded-full text-sm {{ $member->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $member->status == 'active' ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('receptionist.members.edit', $member) }}" class="text-blue-500 hover:text-blue-700"> Modifier</a>
                                    <form method="POST" action="{{ route('receptionist.members.destroy', $member) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce membre ? Cette action est irréversible.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700"> Supprimer</button>
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
