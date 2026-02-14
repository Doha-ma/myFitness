@extends('layouts.admin')

@section('title', 'Membres')

@section('content')
<div class="mb-8">
    <h2 class="text-4xl font-bold text-white mb-2">Membres</h2>
    <p class="text-gray-300">Gestion des membres MyFitness</p>
</div>

<div class="card p-6">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <h3 class="h5 mb-0">Liste des membres</h3>
        <span class="badge bg-info">{{ $members->total() }} membres</span>
    </div>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <label class="form-label">Statut d'abonnement</label>
            <select name="subscription_status" class="form-select">
                <option value="">Tous</option>
                <option value="active" {{ request('subscription_status') === 'active' ? 'selected' : '' }}>Actif</option>
                <option value="expired" {{ request('subscription_status') === 'expired' ? 'selected' : '' }}>Expire</option>
            </select>
        </div>
        <div class="col-md-4 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary">Filtrer</button>
            <a href="{{ route('admin.members.index') }}" class="btn btn-secondary">Reinitialiser</a>
        </div>
    </form>

    @if($members->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Aucun membre</h3>
            <p class="text-gray-500">Aucun membre ne correspond a ce filtre.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Telephone</th>
                        <th>Type d'abonnement</th>
                        <th>Date de fin</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $member)
                        @php
                            $subscription = $member->latestSubscriptionPayment?->subscriptionType;
                            $endDate = $member->resolved_subscription_end_date;
                        @endphp
                        <tr>
                            <td><strong>{{ $member->full_name }}</strong></td>
                            <td>{{ $member->email }}</td>
                            <td>{{ $member->phone ?: '-' }}</td>
                            <td>{{ $subscription?->name ?? '-' }}</td>
                            <td>{{ $endDate ? $endDate->format('d/m/Y') : '-' }}</td>
                            <td>
                                @if($member->subscription_state === 'active')
                                    <span class="badge badge-success">Actif</span>
                                @else
                                    <span class="badge badge-danger">Expire</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.members.edit', $member) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <form method="POST" action="{{ route('admin.members.destroy', $member) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')">
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

    @if($members->hasPages())
        <div class="mt-4">
            {{ $members->links() }}
        </div>
    @endif
</div>
@endsection

