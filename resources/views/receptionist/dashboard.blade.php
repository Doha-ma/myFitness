@extends('layouts.app')

@section('title', 'Tableau de bord Receptionniste')

@section('sidebar')
    <a href="{{ route('receptionist.dashboard') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition bg-white/10"><i class="fas fa-tachometer-alt me-3"></i><span>Tableau de bord</span></a>
    <a href="{{ route('receptionist.members.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition"><i class="fas fa-users me-3"></i><span>Membres</span></a>
    <a href="{{ route('receptionist.payments.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition"><i class="fas fa-money-bill-wave me-3"></i><span>Paiements</span></a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="block w-full text-left px-4 py-3 rounded hover:bg-white/10 transition mt-4"><i class="fas fa-sign-out-alt me-3"></i><span>Deconnexion</span></button>
    </form>
@endsection

@section('content')
<div class="mb-8">
    <h2 class="text-4xl font-bold text-white mb-2">Tableau de bord Receptionniste</h2>
    <p class="text-gray-200">Bienvenue, <span class="font-semibold text-orange-300">{{ auth()->user()->name }}</span></p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="card stat-card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium uppercase tracking-wide">Total membres</p>
                <p class="text-4xl font-bold mt-3" style="color: var(--gym-primary);">{{ $totalMembers }}</p>
                <p class="text-xs text-gray-500 mt-1">Membres enregistres</p>
            </div>
            <div class="text-6xl opacity-20"></div>
        </div>
    </div>

    <div class="card stat-card p-6" style="border-left-color: var(--gym-success);">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium uppercase tracking-wide">Paiements du jour</p>
                <p class="text-4xl font-bold mt-3" style="color: var(--gym-success);">{{ number_format($totalPaymentsToday, 2) }} DH</p>
                <p class="text-xs text-gray-500 mt-1">Encaissement aujourd'hui</p>
            </div>
            <div class="text-6xl opacity-20"></div>
        </div>
    </div>

    <div class="card stat-card p-6" style="border-left-color: var(--gym-secondary);">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium uppercase tracking-wide">Abonnements en cours</p>
                <p class="text-4xl font-bold mt-3" style="color: var(--gym-secondary);">{{ $activeSubscriptionsCount }}</p>
                <p class="text-xs text-gray-500 mt-1">Abonnements actifs</p>
            </div>
            <div class="text-6xl opacity-20"></div>
        </div>
    </div>
</div>

<div class="card p-6 mb-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-bold">Planning des cours et places restantes</h3>
    </div>

    @if($approvedClasses->isEmpty())
        <p class="text-gray-500 text-center py-8">Aucun cours valide disponible.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-3 px-4">Cours</th>
                        <th class="text-left py-3 px-4">Coach</th>
                        <th class="text-left py-3 px-4">Planning</th>
                        <th class="text-left py-3 px-4">Capacite</th>
                        <th class="text-left py-3 px-4">Inscrits</th>
                        <th class="text-left py-3 px-4">Places restantes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($approvedClasses as $class)
                        @php
                            $remaining = max($class->capacity - $class->enrollments_count, 0);
                        @endphp
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4 font-semibold">{{ $class->name }}</td>
                            <td class="py-3 px-4">{{ $class->coach->name ?? '-' }}</td>
                            <td class="py-3 px-4">
                                @if($class->schedules->isEmpty())
                                    <span class="text-sm text-gray-500">Aucun horaire defini</span>
                                @else
                                    <div class="space-y-1">
                                        @foreach($class->schedules as $schedule)
                                            <div class="text-sm text-gray-700">
                                                {{ $schedule->day_of_week }} - {{ $schedule->start_time->format('H:i') }} a {{ $schedule->end_time->format('H:i') }}
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td class="py-3 px-4">{{ $class->capacity }}</td>
                            <td class="py-3 px-4">{{ $class->enrollments_count }}</td>
                            <td class="py-3 px-4">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $remaining > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $remaining }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
    <div class="card p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold">Abonnements en cours</h3>
            <a href="{{ route('receptionist.members.index', ['subscription_status' => 'active']) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Voir tout</a>
        </div>

        @if($activeSubscriptions->isEmpty())
            <p class="text-gray-500 text-center py-6">Aucun abonnement actif.</p>
        @else
            <div class="space-y-3">
                @foreach($activeSubscriptions as $member)
                    <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $member->full_name }}</p>
                                <p class="text-sm text-gray-600">{{ $member->latestSubscriptionPayment?->subscriptionType?->name ?? 'Abonnement non precise' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Fin</p>
                                <p class="font-semibold text-gray-800">{{ optional($member->subscription_end_date)->format('d/m/Y') ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="card p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold">Membres recents</h3>
            <a href="{{ route('receptionist.members.create') }}" class="btn-primary text-white px-4 py-2 rounded-lg font-semibold text-sm">Nouveau membre</a>
        </div>

        @if($recentMembers->isEmpty())
            <p class="text-gray-500 text-center py-6">Aucun membre recent.</p>
        @else
            <div class="space-y-3">
                @foreach($recentMembers as $member)
                    <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $member->full_name }}</p>
                                <p class="text-sm text-gray-600">{{ $member->email }}</p>
                                <p class="text-xs text-gray-500">{{ $member->phone }}</p>
                            </div>
                            <a href="{{ route('receptionist.members.edit', $member) }}" class="text-sm text-blue-600 hover:text-blue-800 font-semibold">Modifier</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

