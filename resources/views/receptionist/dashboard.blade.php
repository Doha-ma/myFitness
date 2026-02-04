@extends('layouts.app')

@section('title', 'Réceptionniste Dashboard')

@section('sidebar')
    <a href="{{ route('receptionist.dashboard') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition bg-white/10"> Dashboard</a>
    <a href="{{ route('receptionist.members.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition"> Membres</a>
    <a href="{{ route('receptionist.payments.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition"> Paiements</a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="block w-full text-left px-4 py-3 rounded hover:bg-white/10 transition mt-4"> Déconnexion</button>
    </form>
@endsection

@section('content')
<div class="mb-8">
    <h2 class="text-4xl font-bold text-white mb-2">Dashboard Réceptionniste</h2>
    <p class="text-gray-200">Bienvenue, <span class="font-semibold text-orange-300">{{ auth()->user()->name }}</span></p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="card stat-card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium uppercase tracking-wide">Total Membres</p>
                <p class="text-4xl font-bold mt-3" style="color: var(--gym-primary);">{{ $totalMembers }}</p>
                <p class="text-xs text-gray-500 mt-1">Membres enregistrés</p>
            </div>
            <div class="text-6xl opacity-20"></div>
        </div>
    </div>

    <div class="card stat-card p-6" style="border-left-color: var(--gym-success);">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium uppercase tracking-wide">Paiements Aujourd'hui</p>
                <p class="text-4xl font-bold mt-3" style="color: var(--gym-success);">{{ number_format($totalPaymentsToday, 2) }} DH</p>
                <p class="text-xs text-gray-500 mt-1">Revenus du jour</p>
            </div>
            <div class="text-6xl opacity-20"></div>
        </div>
    </div>
</div>

<div class="card p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-bold flex items-center">
            <span class="mr-2"></span> Membres Récents
        </h3>
        <a href="{{ route('receptionist.members.create') }}" class="btn-primary text-white px-4 py-2 rounded-lg font-semibold text-sm">
             Nouveau Membre
        </a>
    </div>
    
    @if($recentMembers->isEmpty())
        <div class="text-center py-12">
            <div class="text-6xl mb-4 opacity-50"></div>
            <p class="text-gray-500 text-lg">Aucun membre récent</p>
            <a href="{{ route('receptionist.members.create') }}" class="btn-primary inline-block text-white px-6 py-3 rounded-lg font-semibold mt-4">
                 Ajouter votre premier membre
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($recentMembers as $member)
                <div class="bg-gradient-to-br from-gray-50 to-orange-50 p-4 rounded-lg border border-orange-100 hover:shadow-lg transition">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="font-bold text-gray-800 text-lg">{{ $member->full_name }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $member->email }}</p>
                            <p class="text-xs text-gray-500 mt-1">📞 {{ $member->phone }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $member->status == 'active' ? 'badge-success' : 'bg-red-100 text-red-800' }}">
                            {{ $member->status == 'active' ? '✓ Actif' : '✗ Inactif' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                        <span class="text-xs text-gray-500">Inscrit le {{ $member->join_date->format('d/m/Y') }}</span>
                        <a href="{{ route('receptionist.members.edit', $member) }}" class="text-sm text-blue-600 hover:text-blue-800 font-semibold">
                            Modifier →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
