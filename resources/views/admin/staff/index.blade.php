@extends('layouts.app')

@section('title', 'Gestion Staff')

@section('sidebar')
    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition">📊 Dashboard</a>
    <a href="{{ route('admin.staff.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition bg-white/10">👥 Gestion Staff</a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="block w-full text-left px-4 py-3 rounded hover:bg-white/10 transition">🚪 Déconnexion</button>
    </form>
@endsection

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <h2 class="text-4xl font-bold text-white">Gestion du Staff</h2>
        <a href="{{ route('admin.staff.create') }}" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">
            ➕ Ajouter un Staff
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

<!-- Réceptionnistes -->
<div class="card p-6 mb-6">
    <h3 class="text-2xl font-bold mb-4">🏢 Réceptionnistes</h3>
    @if($receptionists->isEmpty())
        <p class="text-gray-500">Aucun réceptionniste pour le moment</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-3 px-4">Nom</th>
                        <th class="text-left py-3 px-4">Email</th>
                        <th class="text-left py-3 px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receptionists as $receptionist)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $receptionist->name }}</td>
                            <td class="py-3 px-4">{{ $receptionist->email }}</td>
                            <td class="py-3 px-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.staff.edit', $receptionist) }}" class="text-blue-500 hover:text-blue-700">✏️ Modifier</a>
                                    <form method="POST" action="{{ route('admin.staff.destroy', $receptionist) }}" class="inline" onsubmit="return confirm('Supprimer ce staff?')">
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
    @endif
</div>

<!-- Coachs -->
<div class="card p-6">
    <h3 class="text-2xl font-bold mb-4">🏋️ Coachs</h3>
    @if($coaches->isEmpty())
        <p class="text-gray-500">Aucun coach pour le moment</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-3 px-4">Nom</th>
                        <th class="text-left py-3 px-4">Email</th>
                        <th class="text-left py-3 px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($coaches as $coach)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $coach->name }}</td>
                            <td class="py-3 px-4">{{ $coach->email }}</td>
                            <td class="py-3 px-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.staff.edit', $coach) }}" class="text-blue-500 hover:text-blue-700">✏️ Modifier</a>
                                    <form method="POST" action="{{ route('admin.staff.destroy', $coach) }}" class="inline" onsubmit="return confirm('Supprimer ce staff?')">
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
    @endif
</div>
@endsection
