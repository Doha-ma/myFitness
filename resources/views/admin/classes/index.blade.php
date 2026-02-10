@extends('layouts.admin')

@section('title', 'Gestion de cours')

@section('content')
<div class="mb-8">
    <h2 class="text-4xl font-bold text-white mb-2">Gestion de cours</h2>
    <p class="text-gray-300">Gestion de tous les cours validés de la salle de sport</p>
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
        <span class="badge bg-info">{{ $classes->total() }} cours</span>
    </div>

    @if($classes->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-dumbbell text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Aucun cours</h3>
            <p class="text-gray-500">Aucun cours n'a encore été créé dans le système.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="classesTable">
                <thead class="table-dark">
                    <tr>
                        <th>Nom du cours</th>
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
                                <strong>{{ $class->name }}</strong>
                                @if($class->description)
                                    <br><small class="text-muted">{{ \Illuminate\Support\Str::limit($class->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $class->coach->name }}</span>
                                <br><small class="text-muted">{{ $class->coach->email }}</small>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $class->capacity }} pers.</span>
                            </td>
                            <td>
                                <span class="badge {{ $class->enrollments_count >= $class->capacity ? 'bg-warning' : 'bg-success' }}">
                                    {{ $class->enrollments_count }} / {{ $class->capacity }}
                                </span>
                            </td>
                            <td>{{ $class->duration }} min</td>
                            <td>
                                <span class="badge 
                                    {{ $class->status === 'approved' ? 'bg-success' : '' }}
                                    {{ $class->status === 'pending' ? 'bg-warning' : '' }}
                                    {{ $class->status === 'rejected' ? 'bg-danger' : '' }}">
                                    {{ $class->status === 'approved' ? 'Approuvé' : '' }}
                                    {{ $class->status === 'pending' ? 'En attente' : '' }}
                                    {{ $class->status === 'rejected' ? 'Rejeté' : '' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.classes.show', $class) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Voir
                                    </a>
                                    <a href="{{ route('admin.classes.edit', $class) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <form method="POST" action="{{ route('admin.classes.destroy', $class) }}" class="inline">
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
    @if($classes->hasPages())
        <div class="pagination mt-8">
            {{ $classes->links() }}
        </div>
    @endif
</div>
@endsection
