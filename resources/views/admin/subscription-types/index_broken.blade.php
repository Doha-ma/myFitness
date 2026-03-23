@extends('layouts.admin')

@section('title', 'Types d\'Abonnement')

@section('content')
<div class="mb-8">
    <h2 class="text-4xl font-bold text-white mb-2">Types d'Abonnement</h2>
    <p class="text-gray-200">Gérez les différents types d'abonnements disponibles pour vos membres</p>
</div>

<div class="flex justify-between items-center mb-6">
    <div>
        <h3 class="text-xl font-semibold text-white">Liste des abonnements</h3>
        <p class="text-gray-300 text-sm mt-1">{{ $subscriptionTypes->count() }} type(s) d'abonnement</p>
    </div>
    <a href="{{ route('admin.subscription-types.create') }}" class="btn btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        Ajouter un type d'abonnement
    </a>
</div>

            @if(session('success'))
    <div class="alert alert-success">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                Nom
                            </div>
                        </th>
                        <th>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Prix de base
                            </div>
                        </th>
                        <th>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Réduction
                            </div>
                        </th>
                        <th>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Prix final
                            </div>
                        </th>
                        <th>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Durée
                            </div>
                        </th>
                        <th>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Statut
                            </div>
                        </th>
                        <th class="text-center">Actions</th>
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
                                <div class="flex gap-3">
                                    <a href="{{ route('admin.subscription-types.edit', $subscriptionType) }}" 
                                       class="text-blue-600 hover:text-blue-800 font-medium">
                                        <i class="fas fa-edit mr-1"></i>
                                        Modifier
                                    </a>
                                    <form action="{{ route('admin.subscription-types.destroy', $subscriptionType) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('Êtes-vous sur de vouloir supprimer ce type d\'abonnement ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                            <i class="fas fa-trash mr-1"></i>
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Aucun type d'abonnement trouve</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

            @push('scripts')
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

