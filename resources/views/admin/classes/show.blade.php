@extends('layouts.admin')

@section('title', 'Détails du Cours')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.classes.index') }}" class="text-white hover:underline">← Retour aux cours</a>
</div>

<div class="card p-8 mb-6">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h2 class="text-3xl font-bold text-white">{{ $class->name }}</h2>
            <p class="text-gray-300 mt-2">{{ $class->description ?? 'Aucune description' }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.classes.edit', $class) }}" class="btn-primary text-white px-4 py-2 rounded-lg hover:shadow-lg transition">
                 Modifier
            </a>
            <form method="POST" action="{{ route('admin.classes.destroy', $class) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ? Tous les membres seront désinscrits. Cette action est irréversible.')">
                @csrf
                <button type="submit" class="btn btn-danger text-white px-4 py-2 rounded-lg hover:shadow-lg transition">
                    Supprimer
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-600 mb-1">Coach</p>
            <p class="font-semibold">{{ $class->coach->name }}</p>
            <p class="text-sm text-gray-500">{{ $class->coach->email }}</p>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-600 mb-1">Capacité</p>
            <p class="font-semibold">{{ $class->capacity }} personnes</p>
            <p class="text-sm text-gray-500">{{ $class->enrollments_count }} inscrits</p>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-600 mb-1">Durée</p>
            <p class="font-semibold">{{ $class->duration }} minutes</p>
            <p class="text-sm text-gray-500">Par session</p>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-600 mb-1">Statut</p>
            <span class="badge 
                {{ $class->status === 'approved' ? 'bg-success' : '' }}
                {{ $class->status === 'pending' ? 'bg-warning' : '' }}
                {{ $class->status === 'rejected' ? 'bg-danger' : '' }}">
                {{ $class->status === 'approved' ? 'Approuvé' : '' }}
                {{ $class->status === 'pending' ? 'En attente' : '' }}
                {{ $class->status === 'rejected' ? 'Rejeté' : '' }}
            </span>
            @if($class->rejection_reason)
                <p class="text-sm text-gray-500 mt-1">{{ $class->rejection_reason }}</p>
            @endif
        </div>
    </div>

    <!-- Horaires -->
    <div class="mb-8">
        <h3 class="text-xl font-semibold text-white mb-4">Horaires</h3>
        @if($class->schedules->isEmpty())
            <div class="bg-gray-50 p-4 rounded-lg text-center">
                <p class="text-gray-600">Aucun horaire défini pour ce cours.</p>
                <a href="{{ route('admin.classes.edit', $class) }}" class="text-blue-600 hover:underline mt-2 inline-block">
                    Ajouter des horaires
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($class->schedules as $schedule)
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="font-semibold text-gray-800">{{ $schedule->day_of_week }}</p>
                        <p class="text-gray-600">{{ $schedule->start_time }} - {{ $schedule->end_time }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Membres inscrits -->
    <div>
        <h3 class="text-xl font-semibold text-white mb-4">Membres inscrits ({{ $class->enrollments_count }})</h3>
        @if($class->enrollments->isEmpty())
            <div class="bg-gray-50 p-4 rounded-lg text-center">
                <p class="text-gray-600">Aucun membre n'est encore inscrit à ce cours.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nom du membre</th>
                            <th>Email</th>
                            <th>Date d'inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($class->enrollments as $enrollment)
                            <tr>
                                <td>
                                    <strong>{{ $enrollment->member->full_name }}</strong>
                                </td>
                                <td>{{ $enrollment->member->email }}</td>
                                <td>{{ $enrollment->enrollment_date->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.members.edit', $enrollment->member) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> Voir
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@if($class->status === 'pending')
    <!-- Actions pour cours en attente -->
    <div class="card p-6">
        <h3 class="text-xl font-semibold text-white mb-4">Actions de validation</h3>
        <div class="flex gap-4">
            <form method="POST" action="{{ route('admin.classes.approve', $class) }}" class="inline">
                @csrf
                <button type="submit" class="btn btn-success text-white px-6 py-3 rounded-lg hover:shadow-lg transition">
                    <i class="fas fa-check me-2"></i> Approuver ce cours
                </button>
            </form>
            <button type="button" class="btn btn-danger text-white px-6 py-3 rounded-lg hover:shadow-lg transition"
                    data-bs-toggle="modal" 
                    data-bs-target="#rejectModal">
                <i class="fas fa-times me-2"></i> Rejeter ce cours
            </button>
        </div>
    </div>

    <!-- Modal de rejet -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rejeter le cours</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.classes.reject', $class) }}">
                    @csrf
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir rejeter le cours <strong>{{ $class->name }}</strong> ?</p>
                        
                        <div class="mb-3">
                            <label for="rejection_reason" class="form-label">Raison du rejet (optionnel)</label>
                            <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" 
                                      placeholder="Expliquez pourquoi ce cours est rejeté..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times me-2"></i> Rejeter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection
