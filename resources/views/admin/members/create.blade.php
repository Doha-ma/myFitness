@extends('layouts.admin')

@section('title', 'Ajouter Membre')

@section('content')
<div class="mb-8">
    <h2 class="text-4xl font-bold text-white mb-2">Ajouter un membre</h2>
    <p class="text-gray-300">Creation d'un nouveau membre</p>
</div>

<div class="card p-6">
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.members.store') }}">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Prenom</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nom</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Telephone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" required>
            </div>
            <div class="col-12">
                <label class="form-label">Adresse</label>
                <textarea name="address" rows="3" class="form-control">{{ old('address') }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Date d'inscription</label>
                <input type="date" name="join_date" value="{{ old('join_date', now()->format('Y-m-d')) }}" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Statut</label>
                <select name="status" class="form-select" required>
                    <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactif</option>
                </select>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="{{ route('admin.members.index') }}" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>
@endsection
