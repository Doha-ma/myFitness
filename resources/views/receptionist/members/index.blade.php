@extends('layouts.app')

@section('title', 'Gestion Membres')

@section('sidebar')
    <a href="{{ route('receptionist.dashboard') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition">📊 Dashboard</a>
    <a href="{{ route('receptionist.members.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition bg-white/10">👥 Membres</a>
    <a href="{{ route('receptionist.payments.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition">💰 Paiements</a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="block w-full text-left px-4 py-3 rounded hover:bg-white/10 transition">🚪 Déconnexion</button>
    </form>
@endsection

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <h2 class="text-4xl font-bold text-white">Gestion des Membres</h2>
        <a href="{{ route('receptionist.members.create') }}" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">
            ➕ Ajouter un Membre
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
    @if($members->isEmpty())
        <p class="text-gray-500 text-center py-8">Aucun membre enregistré</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-3 px-4">Nom</th>
                        <th class="text-left py-3 px-4">Email</th>
                        <th class="text-left py-3 px-4">Téléphone</th>
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
                            <td class="py-3 px-4">{{ $member->join_date->format('d/m/Y') }}</td>
                            <td class="py-3 px-4">
                                <span class="px-3 py-1 rounded-full text-sm {{ $member->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $member->status == 'active' ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('receptionist.members.edit', $member) }}" class="text-blue-500 hover:text-blue-700">✏️ Modifier</a>
                                    <form method="POST" action="{{ route('receptionist.members.destroy', $member) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce membre ? Cette action est irréversible.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">🗑️ Supprimer</button>
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
