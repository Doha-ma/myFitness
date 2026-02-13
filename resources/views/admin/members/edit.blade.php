@extends('layouts.admin')

@section('title', 'Modifier Membre')

@section('content')
<div class="mb-8">
    <h2 class="text-4xl font-bold text-white mb-2">Modifier membre</h2>
    <p class="text-gray-300">Mise a jour des informations du membre</p>
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

    <form method="POST" action="{{ route('admin.members.update', $member) }}">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Prenom</label>
                <input type="text" name="first_name" value="{{ old('first_name', $member->first_name) }}" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nom</label>
                <input type="text" name="last_name" value="{{ old('last_name', $member->last_name) }}" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $member->email) }}" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Telephone</label>
                <input type="text" name="phone" value="{{ old('phone', $member->phone) }}" class="form-control" required>
            </div>
            <div class="col-12">
                <label class="form-label">Adresse</label>
                <textarea name="address" rows="3" class="form-control">{{ old('address', $member->address) }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Date d'inscription</label>
                <input type="date" name="join_date" value="{{ old('join_date', optional($member->join_date)->format('Y-m-d')) }}" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Statut</label>
                <select name="status" class="form-select" required>
                    <option value="active" {{ old('status', $member->status) === 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="inactive" {{ old('status', $member->status) === 'inactive' ? 'selected' : '' }}>Inactif</option>
                </select>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Mettre a jour</button>
            <a href="{{ route('admin.members.index') }}" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>
@endsection
