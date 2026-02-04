@extends('layouts.app')

@section('title', 'Détails du Cours')

@section('sidebar')
    <a href="{{ route('coach.dashboard') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition"> Dashboard</a>
    <a href="{{ route('coach.classes.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition"> Mes Cours</a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="block w-full text-left px-4 py-3 rounded hover:bg-white/10 transition"> Déconnexion</button>
    </form>
@endsection

@section('content')
<div class="mb-6">
    <a href="{{ route('coach.classes.index') }}" class="text-white hover:underline">← Retour aux cours</a>
</div>

<div class="card p-8 mb-6">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h2 class="text-3xl font-bold">{{ $classModel->name }}</h2>
            <p class="text-gray-600 mt-2">{{ $classModel->description }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('coach.classes.edit', $classModel) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                 Modifier
            </a>
            <form method="POST" action="{{ route('coach.classes.destroy', $classModel) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ? Tous les membres seront désinscrits. Cette action est irréversible.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                     Supprimer
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-4">
        <div class="bg-orange-50 p-4 rounded-lg">
            <p class="text-gray-600 text-sm">Capacité</p>
            <p class="text-2xl font-bold text-orange-600">{{ $classModel->capacity }} personnes</p>
        </div>
        <div class="bg-blue-50 p-4 rounded-lg">
            <p class="text-gray-600 text-sm">Durée</p>
            <p class="text-2xl font-bold text-blue-600">{{ $classModel->duration }} min</p>
        </div>
        <div class="bg-green-50 p-4 rounded-lg">
            <p class="text-gray-600 text-sm">Inscrits</p>
            <p class="text-2xl font-bold text-green-600">{{ $classModel->enrollments->count() }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Schedules -->
    <div class="card p-6">
        <h3 class="text-xl font-bold mb-4"> Horaires</h3>
        
        @if($classModel->schedules->isEmpty())
            <p class="text-gray-500 mb-4">Aucun horaire défini</p>
        @else
            <div class="space-y-2 mb-4">
                @foreach($classModel->schedules as $schedule)
                    <div class="flex justify-between items-center bg-gray-50 p-3 rounded">
                        <div>
                            <span class="font-semibold">{{ $schedule->day_of_week }}</span>
                            <span class="text-gray-600 ml-2">
                                {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                            </span>
                        </div>
                        <form method="POST" action="{{ route('coach.schedules.destroy', [$classModel, $schedule]) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Supprimer cet horaire?')"></button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('coach.schedules.store', $classModel) }}" class="border-t pt-4">
            @csrf
            <h4 class="font-semibold mb-3">Ajouter un horaire</h4>
            <select name="day_of_week" class="w-full px-3 py-2 border rounded mb-2" required>
                <option value="">Jour</option>
                <option value="Monday">Lundi</option>
                <option value="Tuesday">Mardi</option>
                <option value="Wednesday">Mercredi</option>
                <option value="Thursday">Jeudi</option>
                <option value="Friday">Vendredi</option>
                <option value="Saturday">Samedi</option>
                <option value="Sunday">Dimanche</option>
            </select>
            <div class="grid grid-cols-2 gap-2 mb-2">
                <input type="time" name="start_time" class="px-3 py-2 border rounded" required>
                <input type="time" name="end_time" class="px-3 py-2 border rounded" required>
            </div>
            <button type="submit" class="btn-primary w-full text-white px-4 py-2 rounded">
                 Ajouter
            </button>
        </form>
    </div>

    <!-- Members -->
    <div class="card p-6">
        <h3 class="text-xl font-bold mb-4"> Membres Inscrits ({{ $classModel->enrollments->count() }})</h3>
        
        @if($classModel->enrollments->isEmpty())
            <p class="text-gray-500">Aucun membre inscrit pour le moment</p>
        @else
            <div class="space-y-2">
                @foreach($classModel->enrollments as $enrollment)
                    <div class="flex justify-between items-center bg-gray-50 p-3 rounded">
                        <div>
                            <p class="font-semibold">{{ $enrollment->member->full_name }}</p>
                            <p class="text-sm text-gray-600">Inscrit le {{ $enrollment->enrollment_date->format('d/m/Y') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
