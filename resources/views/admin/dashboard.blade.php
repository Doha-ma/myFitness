@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('sidebar')
    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition bg-white/10"> Dashboard</a>
    <a href="{{ route('admin.staff.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition"> Gestion Staff</a>
    <a href="{{ route('admin.subscription-types.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition"> Types d'Abonnement</a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="block w-full text-left px-4 py-3 rounded hover:bg-white/10 transition mt-4"> Déconnexion</button>
    </form>
@endsection

@section('content')
<div class="mb-8">
    <h2 class="text-4xl font-bold text-white mb-2">Dashboard Administrateur</h2>
    <p class="text-gray-200">Bienvenue, <span class="font-semibold text-orange-300">{{ auth()->user()->name }}</span></p>
</div>

<!-- Statistiques principales -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="card stat-card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium uppercase tracking-wide">Membres Inscrits</p>
                <p class="text-4xl font-bold mt-3" style="color: var(--gym-primary);">{{ $totalMembers }}</p>
                <p class="text-xs text-gray-500 mt-1">Total actif</p>
            </div>
            <div class="text-6xl opacity-20"></div>
        </div>
    </div>

    <div class="card stat-card p-6" style="border-left-color: var(--gym-secondary);">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium uppercase tracking-wide">Cours Créés</p>
                <p class="text-4xl font-bold mt-3" style="color: var(--gym-secondary);">{{ $totalClasses }}</p>
                <p class="text-xs text-gray-500 mt-1">En activité</p>
            </div>
            <div class="text-6xl opacity-20"></div>
        </div>
    </div>

    <div class="card stat-card p-6" style="border-left-color: var(--gym-accent);">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium uppercase tracking-wide">Réceptionnistes</p>
                <p class="text-4xl font-bold mt-3" style="color: var(--gym-accent);">{{ $totalReceptionists }}</p>
                <p class="text-xs text-gray-500 mt-1">En service</p>
            </div>
            <div class="text-6xl opacity-20"></div>
        </div>
    </div>

    <div class="card stat-card p-6" style="border-left-color: var(--gym-success);">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium uppercase tracking-wide">Coachs</p>
                <p class="text-4xl font-bold mt-3" style="color: var(--gym-success);">{{ $totalCoaches }}</p>
                <p class="text-xs text-gray-500 mt-1">Disponibles</p>
            </div>
            <div class="text-6xl opacity-20"></div>
        </div>
    </div>
</div>

<!-- Statistiques de paiements -->
<div class="mb-8">
    <h3 class="text-2xl font-bold text-white mb-4"> Statistiques de Paiements</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="card p-6 bg-gradient-to-br from-green-50 to-green-100 border-2 border-green-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-700 text-sm font-medium">Total Paiements</p>
                    <p class="text-3xl font-bold text-green-700 mt-2">{{ number_format($totalPayments, 2) }} DH</p>
                </div>
                <div class="text-5xl"></div>
            </div>
        </div>

        <div class="card p-6 bg-gradient-to-br from-blue-50 to-blue-100 border-2 border-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-700 text-sm font-medium">Ce Mois</p>
                    <p class="text-3xl font-bold text-blue-700 mt-2">{{ number_format($paymentsThisMonth, 2) }} DH</p>
                </div>
                <div class="text-5xl"></div>
            </div>
        </div>

        <div class="card p-6 bg-gradient-to-br from-orange-50 to-orange-100 border-2 border-orange-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-700 text-sm font-medium">Aujourd'hui</p>
                    <p class="text-3xl font-bold text-orange-700 mt-2">{{ number_format($paymentsToday, 2) }} DH</p>
                </div>
                <div class="text-5xl"></div>
            </div>
        </div>

        <div class="card p-6 bg-gradient-to-br from-purple-50 to-purple-100 border-2 border-purple-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-700 text-sm font-medium">Nombre Paiements</p>
                    <p class="text-3xl font-bold text-purple-700 mt-2">{{ $totalPaymentsCount }}</p>
                </div>
                <div class="text-5xl"></div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="card p-6">
        <h3 class="text-xl font-bold mb-4 flex items-center">
            <span class="mr-2"></span> Actions Rapides
        </h3>
        <a href="{{ route('admin.staff.create') }}" class="btn-primary block text-center text-white px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition">
             Ajouter un Staff
        </a>
        <a href="{{ route('admin.staff.index') }}" class="block text-center bg-gray-100 hover:bg-gray-200 text-gray-800 px-6 py-3 rounded-lg font-semibold mt-3 transition">
             Voir tous les Staff
        </a>
    </div>

    <div class="card p-6">
        <h3 class="text-xl font-bold mb-4 flex items-center">
            <span class="mr-2"></span> Paiements Récents
        </h3>
        @if($recentPayments->isEmpty())
            <div class="text-center py-8">
                <p class="text-gray-500 text-lg">Aucun paiement récent</p>
                <p class="text-gray-400 text-sm mt-2">Les paiements apparaîtront ici</p>
            </div>
        @else
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @foreach($recentPayments as $payment)
                    <div class="flex justify-between items-center bg-gradient-to-r from-gray-50 to-orange-50 p-4 rounded-lg border border-orange-100 hover:shadow-md transition">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $payment->member->full_name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $payment->payment_date->format('d/m/Y à H:i') }}</p>
                            <p class="text-xs text-gray-500 mt-1">Par: {{ $payment->receptionist->name ?? 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-green-600 text-lg">{{ number_format($payment->amount, 2) }} DH</p>
                            <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold badge-primary mt-1">
                                {{ ucfirst($payment->method) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
