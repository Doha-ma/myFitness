@extends('layouts.app')

@section('title', 'Coach Dashboard')

@section('sidebar')
    <a href="{{ route('coach.dashboard') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition bg-white/10"> Dashboard</a>
    <a href="{{ route('coach.classes.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition"> Mes Cours</a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="block w-full text-left px-4 py-3 rounded hover:bg-white/10 transition mt-4"> Déconnexion</button>
    </form>
@endsection

@section('content')
<div class="mb-8">
    <h2 class="text-4xl font-bold text-white mb-2">Dashboard Coach</h2>
    <p class="text-gray-200">Bienvenue, <span class="font-semibold text-orange-300">{{ auth()->user()->name }}</span></p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="card stat-card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium uppercase tracking-wide">Mes Cours</p>
                <p class="text-4xl font-bold mt-3" style="color: var(--gym-primary);">{{ $totalClasses }}</p>
                <p class="text-xs text-gray-500 mt-1">Cours créés</p>
            </div>
            <div class="text-6xl opacity-20"></div>
        </div>
    </div>

    <div class="card stat-card p-6" style="border-left-color: var(--gym-success);">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium uppercase tracking-wide">Total Inscriptions</p>
                <p class="text-4xl font-bold mt-3" style="color: var(--gym-success);">{{ $totalEnrollments }}</p>
                <p class="text-xs text-gray-500 mt-1">Membres inscrits</p>
            </div>
            <div class="text-6xl opacity-20">👥</div>
        </div>
    </div>
</div>

<div class="card p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-bold flex items-center">
            <span class="mr-2"></span> Mes Cours
        </h3>
        <div class="flex gap-3">
            <!-- Class Filter -->
            <form method="GET" action="{{ route('coach.dashboard') }}" class="flex items-center gap-2">
                <select name="class_id" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous les cours</option>
                    @foreach($allClasses as $class)
                        <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                            {{ $class->name }} ({{ $class->enrollments_count }} membres)
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold transition">
                    Filtrer
                </button>
                @if($selectedClassId)
                    <a href="{{ route('coach.dashboard') }}" class="text-gray-500 hover:text-gray-700">
                        ✕
                    </a>
                @endif
            </form>
            <a href="{{ route('coach.classes.create') }}" class="btn-primary text-white px-4 py-2 rounded-lg font-semibold">
                 Nouveau Cours
            </a>
        </div>
    </div>

    <!-- Class-specific statistics -->
    @if($classStats)
        <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <h4 class="font-bold text-lg text-blue-800 mb-3">
                Statistiques pour: {{ $classStats['class']->name }}
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center">
                    <p class="text-3xl font-bold text-blue-600">{{ $classStats['enrollment_count'] }}</p>
                    <p class="text-sm text-gray-600">Membres inscrits</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-green-600">{{ $classStats['capacity_utilization'] }}%</p>
                    <p class="text-sm text-gray-600">Taux de remplissage</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-purple-600">{{ $classStats['class']->capacity }}</p>
                    <p class="text-sm text-gray-600">Capacité totale</p>
                </div>
            </div>
            
            @if($classStats['recent_enrollments']->isNotEmpty())
                <div class="mt-4">
                    <h5 class="font-semibold text-sm text-gray-700 mb-2">Inscriptions récentes:</h5>
                    <div class="space-y-1">
                        @foreach($classStats['recent_enrollments'] as $enrollment)
                            <div class="text-xs text-gray-600">
                                {{ $enrollment->member->full_name }} - {{ $enrollment->enrollment_date->format('d/m/Y') }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

    @if($classes->isEmpty())
        <div class="text-center py-12">
            <div class="text-6xl mb-4 opacity-50"></div>
            <p class="text-gray-500 text-lg">Aucun cours trouvé</p>
            <a href="{{ route('coach.classes.create') }}" class="btn-primary inline-block text-white px-6 py-3 rounded-lg font-semibold mt-4">
                 Créer votre premier cours
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($classes as $class)
                <div class="bg-gradient-to-br from-gray-50 to-blue-50 p-5 rounded-lg border border-blue-100 hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="flex justify-between items-start mb-3">
                        <h4 class="font-bold text-lg text-gray-800">{{ $class->name }}</h4>
                        <span class="badge-primary px-2 py-1 rounded-full text-xs font-semibold">
                            {{ $class->enrollments_count ?? $class->enrollments->count() }} membres
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ Str::limit($class->description ?? 'Aucune description', 80) }}</p>
                    
                    <!-- Capacity utilization bar -->
                    <div class="mb-3">
                        <div class="flex justify-between text-xs text-gray-600 mb-1">
                            <span>Remplissage</span>
                            <span>{{ $class->capacity > 0 ? round(($class->enrollments_count / $class->capacity) * 100, 0) : 0 }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $class->capacity > 0 ? min(($class->enrollments_count / $class->capacity) * 100, 100) : 0 }}%"></div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                        <div class="text-xs text-gray-500">
                            <span class="font-semibold">Capacité:</span> {{ $class->capacity }} personnes
                        </div>
                        <a href="{{ route('coach.classes.show', $class) }}" class="text-sm font-semibold" style="color: var(--gym-primary);">
                            Voir détails →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
