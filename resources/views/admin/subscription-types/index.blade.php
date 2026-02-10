@extends('layouts.admin')

@section('title', 'Types d\'Abonnement')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Types d'abonnement</h1>
    <a href="{{ route('admin.subscription-types.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Ajouter un type d'abonnement
    </a>
</div>

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

            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="subscriptionTypesTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Prix de base</th>
                                    <th>Réduction</th>
                                    <th>Prix final</th>
                                    <th>Durée</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subscriptionTypes as $subscriptionType)
                                    <tr>
                                        <td>
                                            {{ $subscriptionType->name }}
                                            @if($subscriptionType->description)
                                                <br><small class="text-muted">{{ Str::limit($subscriptionType->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>{{ number_format($subscriptionType->base_price, 2, ',', ' ') }} DH</td>
                                        <td>
                                            @if($subscriptionType->discount_value > 0)
                                                @if($subscriptionType->discount_type === 'percentage')
                                                    {{ $subscriptionType->discount_value }}%
                                                @else
                                                    {{ number_format($subscriptionType->discount_value, 2, ',', ' ') }} DH
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="fw-bold text-success">
                                            {{ $subscriptionType->formatted_price }}
                                        </td>
                                        <td>{{ $subscriptionType->duration_days }} jours</td>
                                        <td>
                                            @if($subscriptionType->is_active)
                                                <span class="badge bg-success">Actif</span>
                                            @else
                                                <span class="badge bg-secondary">Inactif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.subscription-types.edit', $subscriptionType) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.subscription-types.destroy', $subscriptionType) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce type d\'abonnement ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Aucun type d'abonnement trouvé</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            @if($subscriptionTypes->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $subscriptionTypes->links() }}
                </div>
            @endif
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#subscriptionTypesTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json'
            },
            pageLength: 25,
            order: [[0, 'asc']]
        });
    });
</script>
@endpush
