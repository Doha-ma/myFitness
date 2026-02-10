@extends('layouts.admin')

@section('title', 'Validation des Cours')

@section('content')
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-4xl font-bold text-white mb-2 flex items-center gap-3">
                <i class="fas fa-check-circle text-yellow-400"></i>
                Validation des Cours
            </h2>
            <p class="text-gray-300 text-sm">Approuvez ou rejetez les cours créés par les coachs</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Dashboard
            </a>
            <a href="{{ route('admin.classes.index') }}" class="btn btn-info">
                <i class="fas fa-list"></i>
                Tous les cours
            </a>
        </div>
    </div>
</div>

<div class="card p-6">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-3"></i>
                <div>
                    <strong>Succès!</strong> {{ session('success') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-3"></i>
                <div>
                    <strong>Erreur!</strong> {{ session('error') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($pendingClasses->isEmpty())
        <div class="text-center py-16">
            <div class="inline-flex flex-col items-center">
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-check-circle text-4xl text-green-500"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-700 mb-3">Aucun cours en attente</h3>
                <p class="text-gray-500 max-w-md mx-auto">
                    Tous les cours ont été validés. Il n'y a aucun cours en attente de validation pour le moment.
                </p>
                <div class="mt-6">
                    <a href="{{ route('admin.classes.index') }}" class="btn btn-primary">
                        <i class="fas fa-eye me-2"></i>
                        Voir tous les cours
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-lg border border-blue-200">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-clock text-white"></i>
                    </div>
                    <div>
                        <p class="text-sm text-blue-600 font-medium">En attente</p>
                        <p class="text-2xl font-bold text-blue-900">{{ $pendingClasses->total() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-lg border border-green-200">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-user-tie text-white"></i>
                    </div>
                    <div>
                        <p class="text-sm text-green-600 font-medium">Coachs concernés</p>
                        <p class="text-2xl font-bold text-green-900">{{ $pendingClasses->pluck('coach_id')->unique()->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 p-4 rounded-lg border border-yellow-200">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-calendar text-white"></i>
                    </div>
                    <div>
                        <p class="text-sm text-yellow-600 font-medium">Créés aujourd'hui</p>
                        <p class="text-2xl font-bold text-yellow-900">{{ $pendingClasses->where('created_at', '>=', now()->startOfDay())->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau des cours -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-list-check mr-2 text-yellow-500"></i>
                        Cours en attente de validation
                    </h3>
                    <span class="badge badge-warning">
                        <i class="fas fa-hourglass-half mr-1"></i>
                        {{ $pendingClasses->total() }} cours
                    </span>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th class="text-left">
                                <i class="fas fa-dumbbell mr-2"></i>Nom du cours
                            </th>
                            <th class="text-left">
                                <i class="fas fa-user-tie mr-2"></i>Coach
                            </th>
                            <th class="text-center">
                                <i class="fas fa-users mr-2"></i>Capacité
                            </th>
                            <th class="text-center">
                                <i class="fas fa-clock mr-2"></i>Durée
                            </th>
                            <th class="text-left">
                                <i class="fas fa-calendar-plus mr-2"></i>Créé le
                            </th>
                            <th class="text-center">
                                <i class="fas fa-cogs mr-2"></i>Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingClasses as $class)
                            <tr class="hover:bg-yellow-50 transition-colors duration-200">
                                <td class="py-4">
                                    <div class="flex flex-col">
                                        <strong class="text-gray-900 font-medium">{{ $class->name }}</strong>
                                        @if($class->description)
                                            <p class="text-sm text-gray-600 mt-1 max-w-xs">
                                                {{ \Illuminate\Support\Str::limit($class->description, 80) }}
                                            </p>
                                        @endif>
                                        <div class="flex gap-2 mt-2">
                                            @if($class->schedules->count() > 0)
                                                <span class="badge badge-info">
                                                    <i class="fas fa-calendar-alt mr-1"></i>
                                                    {{ $class->schedules->count() }} séances
                                                </span>
                                            @endif
                                            @if($class->enrollments->count() > 0)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-users mr-1"></i>
                                                    {{ $class->enrollments->count() }} inscrits
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user-tie text-blue-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $class->coach->name }}</div>
                                            <div class="text-sm text-gray-600">{{ $class->coach->email }}</div>
                                        </div>
                                    </td>
                                <td class="py-4 text-center">
                                    <span class="badge badge-primary">
                                        <i class="fas fa-users mr-1"></i>
                                        {{ $class->capacity }} pers.
                                    </span>
                                </td>
                                <td class="py-4 text-center">
                                    <div class="flex items-center justify-center">
                                        <i class="fas fa-clock text-gray-500 mr-2"></i>
                                        <span class="font-medium text-gray-900">{{ $class->duration }} min</span>
                                    </div>
                                </td>
                                <td class="py-4">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-calendar-plus mr-2"></i>
                                        {{ $class->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </td>
                                <td class="py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" 
                                                class="btn btn-success btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#approveModal{{ $class->id }}"
                                                title="Approuver ce cours">
                                            <i class="fas fa-check"></i>
                                            Approuver
                                        </button>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#rejectModal{{ $class->id }}"
                                                title="Rejeter ce cours">
                                            <i class="fas fa-times"></i>
                                            Rejeter
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($pendingClasses->hasPages())
            <div class="pagination mt-8">
                {{ $pendingClasses->links() }}
            </div>
        @endif
    @endif
</div>

<!-- Modaux d'approbation et de rejet -->
@foreach($pendingClasses as $class)
    <!-- Modal d'approbation -->
    <div class="modal fade" id="approveModal{{ $class->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-green-50 border-green-200">
                    <h5 class="modal-title flex items-center">
                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                        Approuver le cours
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.classes.approve', $class) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="bg-green-50 p-4 rounded-lg mb-4">
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-dumbbell text-green-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-green-900">{{ $class->name }}</h4>
                                    <p class="text-sm text-green-700 mt-1">{{ $class->description ?: 'Aucune description' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Confirmation:</strong> Êtes-vous sûr de vouloir approuver ce cours ? 
                            Une fois approuvé, il sera visible pour tous les membres et disponible pour les inscriptions.
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div class="bg-gray-50 p-3 rounded">
                                <span class="text-gray-600">Coach:</span>
                                <span class="font-medium">{{ $class->coach->name }}</span>
                            </div>
                            <div class="bg-gray-50 p-3 rounded">
                                <span class="text-gray-600">Capacité:</span>
                                <span class="font-medium">{{ $class->capacity }} personnes</span>
                            </div>
                            <div class="bg-gray-50 p-3 rounded">
                                <span class="text-gray-600">Durée:</span>
                                <span class="font-medium">{{ $class->duration }} minutes</span>
                            </div>
                            <div class="bg-gray-50 p-3 rounded">
                                <span class="text-gray-600">Créé le:</span>
                                <span class="font-medium">{{ $class->created_at->format('d/m/Y à H:i') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-gray-50">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times mr-2"></i>
                            Annuler
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check mr-2"></i>
                            Approuver le cours
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de rejet -->
    <div class="modal fade" id="rejectModal{{ $class->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-red-50 border-red-200">
                    <h5 class="modal-title flex items-center">
                        <i class="fas fa-times-circle text-red-600 mr-2"></i>
                        Rejeter le cours
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.classes.reject', $class) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="bg-red-50 p-4 rounded-lg mb-4">
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-dumbbell text-red-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-red-900">{{ $class->name }}</h4>
                                    <p class="text-sm text-red-700 mt-1">{{ $class->description ?: 'Aucune description' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Attention:</strong> Cette action est irréversible. 
                            Le cours sera définitivement rejeté et ne pourra pas être récupéré.
                        </div>
                        
                        <div class="mb-4">
                            <label for="rejection_reason_{{ $class->id }}" class="form-label font-medium text-gray-700">
                                <i class="fas fa-comment-alt mr-2"></i>
                                Raison du rejet <span class="text-gray-500">(optionnel)</span>
                            </label>
                            <textarea class="form-control" 
                                      id="rejection_reason_{{ $class->id }}" 
                                      name="rejection_reason" 
                                      rows="4" 
                                      placeholder="Expliquez pourquoi ce cours est rejeté..."></textarea>
                            <p class="text-sm text-gray-600 mt-1">
                                Cette raison sera communiquée au coach pour l'aider à améliorer ses futures propositions.
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer bg-gray-50">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Retour
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times mr-2"></i>
                            Rejeter le cours
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@push('scripts')
<script>
$(document).ready(function() {
    $('#pendingClassesTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr.json',
        },
        pageLength: 25,
        order: [[0, 'desc']],
        responsive: true
    });
});
</script>
@endpush
@endsection
