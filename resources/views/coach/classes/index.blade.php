@extends('layouts.app')

@section('title', 'Mes Cours')

@section('sidebar')
    <a href="{{ route('coach.dashboard') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition">📊 Dashboard</a>
    <a href="{{ route('coach.classes.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition bg-white/10">🏋️ Mes Cours</a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="block w-full text-left px-4 py-3 rounded hover:bg-white/10 transition">🚪 Déconnexion</button>
    </form>
@endsection

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <h2 class="text-4xl font-bold text-white">Mes Cours</h2>
        <a href="{{ route('coach.classes.create') }}" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">
            ➕ Créer un Cours
        </a>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
@endif

@if($classes->isEmpty())
    <div class="card p-8 text-center">
        <p class="text-gray-500 text-lg mb-4">Aucun cours créé pour le moment</p>
        <a href="{{ route('coach.classes.create') }}" class="btn-primary inline-block text-white px-6 py-3 rounded-lg font-semibold">
            ➕ Créer votre premier cours
        </a>
    </div>
@else
    <div class="card p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-3 px-4">Nom du Cours</th>
                        <th class="text-left py-3 px-4">Description</th>
                        <th class="text-left py-3 px-4">Capacité</th>
                        <th class="text-left py-3 px-4">Membres Inscrits</th>
                        <th class="text-left py-3 px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classes as $class)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4 font-semibold">{{ $class->name }}</td>
                            <td class="py-3 px-4">{{ Str::limit($class->description, 50) }}</td>
                            <td class="py-3 px-4">{{ $class->capacity }} personnes</td>
                            <td class="py-3 px-4">
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ $class->enrollments_count ?? $class->enrollments->count() }} membres
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('coach.classes.show', $class) }}" class="text-blue-500 hover:text-blue-700">👁️ Voir</a>
                                    <a href="{{ route('coach.classes.edit', $class) }}" class="text-orange-500 hover:text-orange-700">✏️ Modifier</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $classes->links() }}
        </div>
    </div>
@endif
@endsection
