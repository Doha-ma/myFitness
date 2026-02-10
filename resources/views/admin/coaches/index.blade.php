@extends('layouts.admin')

@section('title', 'Coachs')

@section('content')
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-4xl font-bold text-white mb-2">Coachs</h2>
            <p class="text-gray-300">Gestion de tous les coachs de la salle de sport</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.coaches.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter un Coach
            </a>
        </div>
    </div>
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
        <h3 class="h5 mb-0">Liste des Coachs</h3>
        <span class="badge bg-info">{{ $coaches->total() }} coachs</span>
    </div>

    @if($coaches->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-user-tie text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Aucun coach</h3>
            <p class="text-gray-500">Aucun coach n'a encore été ajouté au système.</p>
            <div class="mt-4">
                <a href="{{ route('admin.coaches.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter le premier coach
                </a>
            </div>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="coachesTable">
                <thead class="table-dark">
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Cours</th>
                        <th>Date de création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($coaches as $coach)
                        <tr>
                            <td>
                                <strong>{{ $coach->name }}</strong>
                                @if($coach->email)
                                    <br><small class="text-muted">{{ $coach->email }}</small>
                                @endif
                            </td>
                            <td>
                                @if($coach->email)
                                    <span class="badge bg-info">{{ $coach->email }}</span>
                                    <br><small class="text-muted">{{ $coach->email }}</small>
                                @else
                                    <span class="badge bg-secondary">Non renseigné</span>
                                @endif
                            </td>
                            <td>{{ $coach->phone ?? 'Non renseigné' }}</td>
                            <td>{{ $coach->classes_as_coach_count ?? 0 }}</td>
                            <td>{{ $coach->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.coaches.edit', $coach) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <form method="POST" action="{{ route('admin.coaches.destroy', $coach) }}" class="inline">
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
    @if($coaches->hasPages())
        <div class="pagination mt-8">
            {{ $coaches->links() }}
        </div>
    @endif
</div>
@endsection
