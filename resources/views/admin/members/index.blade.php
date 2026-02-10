@extends('layouts.admin')

@section('title', 'Membres')

@section('content')
<div class="mb-8">
    <h2 class="text-4xl font-bold text-white mb-2">Membres</h2>
    <p class="text-gray-300">Gestion de tous les membres de la salle de sport</p>
</div>

<div class="card p-6">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="h5 mb-0">Liste des Membres</h3>
        <span class="badge bg-info">{{ $members->total() }} membres</span>
    </div>

    @if($members->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Aucun membre</h3>
            <p class="text-gray-500">Aucun membre n'a encore été ajouté au système.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="membersTable">
                <thead class="table-dark">
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Date d'inscription</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $member)
                        <tr>
                            <td>
                                <strong>{{ $member->name }}</strong>
                                @if($member->email)
                                    <br><small class="text-muted">{{ $member->email }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $member->email }}</span>
                                <br><small class="text-muted">{{ $member->email }}</small>
                            </td>
                            <td>{{ $member->phone ?? 'Non renseigné' }}</td>
                            <td>{{ $member->created_at->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-success">Actif</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.members.edit', $member) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <form method="POST" action="{{ route('admin.members.destroy', $member) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Pagination -->
    @if($members->hasPages())
        <div class="pagination mt-8">
            {{ $members->links() }}
        </div>
    @endif
</div>
@endsection
