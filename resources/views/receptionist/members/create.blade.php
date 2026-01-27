@extends('layouts.app')

@section('title', 'Ajouter Membre')

@section('sidebar')
    <a href="{{ route('receptionist.dashboard') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition">📊 Dashboard</a>
    <a href="{{ route('receptionist.members.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition bg-white/10">👥 Membres</a>
    <a href="{{ route('receptionist.payments.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition">💰 Paiements</a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="block w-full text-left px-4 py-3 rounded hover:bg-white/10 transition mt-4">🚪 Déconnexion</button>
    </form>
@endsection

@section('content')
<div class="mb-6">
    <h2 class="text-3xl font-bold text-white mb-2">Ajouter un nouveau membre</h2>
    <p class="text-gray-300">Enregistrez les informations d'un nouveau membre</p>
</div>

<div class="card p-8 max-w-3xl">
    @if($errors->any())
        <div class="bg-red-50 border-2 border-red-300 text-red-700 px-4 py-3 rounded-lg mb-6">
            <strong>⚠️ Erreurs :</strong>
            <ul class="list-disc list-inside mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form method="POST" action="{{ route('receptionist.members.store') }}">
        @csrf
        
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-800 font-semibold mb-2">👤 Prénom</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" 
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none transition" 
                       placeholder="Ex: Jean"
                       required>
                @error('first_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-gray-800 font-semibold mb-2">👤 Nom</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" 
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none transition" 
                       placeholder="Ex: Dupont"
                       required>
                @error('last_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-800 font-semibold mb-2">📧 Email</label>
                <input type="email" name="email" value="{{ old('email') }}" 
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none transition" 
                       placeholder="exemple@email.com"
                       required>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-gray-800 font-semibold mb-2">📞 Téléphone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" 
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none transition" 
                       placeholder="+33 6 12 34 56 78"
                       required>
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-gray-800 font-semibold mb-2">📍 Adresse</label>
            <textarea name="address" rows="3" 
                      class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none transition"
                      placeholder="Adresse complète du membre">{{ old('address') }}</textarea>
            @error('address')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-800 font-semibold mb-2">📅 Date d'inscription</label>
                <input type="date" name="join_date" value="{{ old('join_date', date('Y-m-d')) }}" 
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none transition" 
                       required>
                @error('join_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-gray-800 font-semibold mb-2">⚡ Statut</label>
                <select name="status" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none transition" required>
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>✓ Actif</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>✗ Inactif</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Course selection section - allows selecting multiple courses for enrollment --}}
        <div class="mb-6">
            <label class="block text-gray-800 font-semibold mb-2">🏋️ Cours à s'abonner (optionnel)</label>
            <p class="text-sm text-gray-600 mb-3">Sélectionnez un ou plusieurs cours auxquels ce membre souhaite s'abonner</p>
            @if(isset($classes) && $classes->isEmpty())
                <div class="bg-yellow-50 border-2 border-yellow-300 text-yellow-800 px-4 py-3 rounded-lg">
                    <p class="text-sm">⚠️ Aucun cours disponible pour le moment. Les cours doivent être créés par les coachs.</p>
                </div>
            @elseif(isset($classes))
                <div class="border-2 border-gray-300 rounded-lg p-4 max-h-64 overflow-y-auto">
                    @foreach($classes as $class)
                        <label class="flex items-center p-3 mb-2 hover:bg-gray-50 rounded-lg cursor-pointer border border-gray-200">
                            <input type="checkbox" name="classes[]" value="{{ $class->id }}" 
                                   class="mr-3 w-5 h-5 accent-orange-500"
                                   {{ in_array($class->id, old('classes', [])) ? 'checked' : '' }}>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">{{ $class->name }}</p>
                                <p class="text-sm text-gray-600">
                                    Coach: {{ $class->coach->name ?? 'N/A' }} | 
                                    Capacité: {{ $class->capacity }} | 
                                    Durée: {{ $class->duration }} min
                                </p>
                                @if($class->description)
                                    <p class="text-xs text-gray-500 mt-1">{{ Str::limit($class->description, 60) }}</p>
                                @endif
                            </div>
                        </label>
                    @endforeach
                </div>
            @endif
            @error('classes')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            @error('classes.*')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-4 pt-4">
            <button type="submit" class="btn-primary text-white px-8 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition">
                ✅ Créer le Membre
            </button>
            <a href="{{ route('receptionist.members.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-8 py-3 rounded-lg font-semibold transition">
                ❌ Annuler
            </a>
        </div>
    </form>
</div>
@endsection
