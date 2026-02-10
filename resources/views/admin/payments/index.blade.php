@extends('layouts.admin')

@section('title', 'Paiements')

@section('content')
<div class="mb-8">
    <h2 class="text-4xl font-bold text-white mb-2">Paiements</h2>
    <p class="text-gray-300">Historique de tous les paiements enregistrés</p>
</div>

<div class="card p-6">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="h5 mb-0">Historique des paiements</h3>
        <span class="badge bg-info">{{ $payments->total() }} paiements</span>
    </div>

    @if($payments->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-money-bill-wave text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Aucun paiement</h3>
            <p class="text-gray-500">Aucun paiement n'a encore été enregistré.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="paymentsTable">
                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Membre</th>
                        <th>Montant</th>
                        <th>Méthode</th>
                        <th>Réceptionniste</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                            <td>
                                <strong>{{ $payment->member->full_name }}</strong>
                                <br><small class="text-muted">{{ $payment->member->email }}</small>
                            </td>
                            <td>
                                <span class="badge bg-success">{{ number_format($payment->amount, 2) }} DH</span>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ ucfirst($payment->method) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $payment->receptionist->name }}</span>
                                <br><small class="text-muted">{{ $payment->receptionist->email }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($payments->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $payments->links() }}
            </div>
        @endif
    @endif
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#paymentsTable').DataTable({
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
