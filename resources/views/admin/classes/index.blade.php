@extends('layouts.admin')

@section('title', 'Gestion de cours')

@section('content')
<div class="mb-8">
    <h2 class="text-4xl font-bold text-white mb-2">Gestion de cours</h2>
    <p class="text-gray-300">Gestion de tous les cours de la salle de sport (valides, en attente, rejetes)</p>
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
        <h3 class="h5 mb-0">Liste des Cours</h3>
        <div class="d-flex gap-2 align-items-center">
            <span class="badge bg-info">{{ $classes->total() }} cours</span>
            <a href="{{ route('admin.classes.pending') }}" class="btn btn-warning btn-sm">
                <i class="fas fa-check-circle"></i> Validation
                @if(($pendingClassesCount ?? 0) > 0)
                    <span class="badge bg-danger">{{ $pendingClassesCount }}</span>
                @endif
            </a>
        </div>
    </div>

    @if($classes->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-dumbbell text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Aucun cours</h3>
            <p class="text-gray-500">Aucun cours n'a encore ete cree dans le systeme.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover compact-table" id="classesTable">
                <thead class="table-dark">
                    <tr>
                        <th>Cours</th>
                        <th>Coach</th>
                        <th>Capacité</th>
                        <th>Inscrits</th>
                        <th>Durée</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classes as $class)
                        <tr>
                            <td>
                                <strong>{{ Str::limit($class->name, 25) }}</strong>
                                @if($class->description)
                                    <br><small class="text-muted">{{ Str::limit($class->description, 30) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ Str::limit($class->coach->name, 15) }}</span>
                                <br><small class="text-muted">{{ Str::limit($class->coach->email, 20) }}</small>
                            </td>
                            <td>{{ $class->capacity }}</td>
                            <td>{{ $class->enrollments_count ?? 0 }}</td>
                            <td>{{ $class->duration }} min</td>
                            <td>
                                <span class="badge 
                                    {{ $class->status === 'approved' ? 'bg-success' : '' }}
                                    {{ $class->status === 'pending' ? 'bg-warning' : '' }}
                                    {{ $class->status === 'rejected' ? 'bg-danger' : '' }}">
                                    {{ $class->status === 'approved' ? 'Approuve' : '' }}
                                    {{ $class->status === 'pending' ? 'En attente' : '' }}
                                    {{ $class->status === 'rejected' ? 'Rejete' : '' }}
                                </span>
                            </td>
                            <td>
                                <div class="flex gap-3">
                                    <a href="{{ route('admin.classes.show', $class) }}" class="text-green-600 hover:text-green-800 font-medium">
                                        <i class="fas fa-eye mr-1"></i> Voir
                                    </a>
                                    <a href="{{ route('admin.classes.edit', $class) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                        <i class="fas fa-edit mr-1"></i> Modifier
                                    </a>
                                    <form method="POST" action="{{ route('admin.classes.destroy', $class) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                            <i class="fas fa-trash mr-1"></i> Supprimer
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
    @if($classes->hasPages())
        <div class="pagination mt-8">
            {{ $classes->links() }}
        </div>
    @endif
</div>
@endsection


