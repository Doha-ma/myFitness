@extends('layouts.app')

@section('title', 'Gestion Paiements')

@section('sidebar')
    <a href="{{ route('receptionist.dashboard') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition"><i class="fas fa-tachometer-alt me-3"></i><span>Dashboard</span></a>
    <a href="{{ route('receptionist.members.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition"><i class="fas fa-users me-3"></i><span>Membres</span></a>
    <a href="{{ route('receptionist.payments.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition bg-white/10"><i class="fas fa-money-bill-wave me-3"></i><span>Paiements</span></a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="block w-full text-left px-4 py-3 rounded hover:bg-white/10 transition"><i class="fas fa-sign-out-alt me-3"></i><span>Deconnexion</span></button>
    </form>
@endsection

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <h2 class="text-4xl font-bold text-white">Gestion des Paiements</h2>
        <a href="{{ route('receptionist.payments.create') }}" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">
             Enregistrer un Paiement
        </a>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
@endif

<div class="card p-6">
    @if($payments->isEmpty())
        <p class="text-gray-500 text-center py-8">Aucun paiement enregistre</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-3 px-4">Membre</th>
                        <th class="text-left py-3 px-4">Type d'abonnement</th>
                        <th class="text-left py-3 px-4">Montant</th>
                        <th class="text-left py-3 px-4">Date</th>
                        <th class="text-left py-3 px-4">Methode</th>
                        <th class="text-left py-3 px-4">Enregistre par</th>
                        <th class="text-left py-3 px-4">Notes</th>
                        <th class="text-left py-3 px-4">Email</th>
                        <th class="text-left py-3 px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4 font-semibold">{{ $payment->member->full_name ?? 'N/A' }}</td>
                            <td class="py-3 px-4">
                                @if($payment->subscriptionType)
                                    <span class="px-2 py-1 rounded-full text-sm bg-purple-100 text-purple-800">
                                        {{ $payment->subscriptionType->name }}
                                    </span>
                                @else
                                    <span class="text-gray-500 text-sm">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-bold text-green-600">{{ number_format($payment->amount, 2) }} DH</span>
                            </td>
                            <td class="py-3 px-4">{{ $payment->payment_date->format('d/m/Y') }}</td>
                            <td class="py-3 px-4">
                                <span class="px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                                    {{ ucfirst($payment->method) }}
                                </span>
                            </td>
                            <td class="py-3 px-4">{{ $payment->receptionist->name ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-sm text-gray-600">{{ Str::limit($payment->notes ?? '', 30) }}</td>
                            <td class="py-3 px-4">
                                @if($payment->email_status === 'sent')
                                    <span class="px-2 py-1 rounded-full text-sm bg-green-100 text-green-800" title="Envoyé le {{ $payment->email_sent_at?->format('d/m/Y H:i') }}">
                                        <i class="fas fa-check-circle me-1"></i>Envoyé
                                    </span>
                                @elseif($payment->email_status === 'failed')
                                    <span class="px-2 py-1 rounded-full text-sm bg-red-100 text-red-800" title="{{ $payment->email_error }}">
                                        <i class="fas fa-exclamation-circle me-1"></i>Échec
                                    </span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-sm bg-gray-100 text-gray-800">
                                        <i class="fas fa-clock me-1"></i>En attente
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('receptionist.payments.invoice', $payment) }}" 
                                       class="btn-primary text-white px-3 py-2 rounded-lg text-sm font-semibold hover:shadow-lg transition inline-block"
                                       target="_blank"
                                       title="Telecharger la facture PDF">
                                         PDF
                                    </a>
                                    
                                    @if($payment->member->email && $payment->email_status !== 'sent')
                                        <button onclick="resendEmail({{ $payment->id }})" 
                                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg text-sm font-semibold transition inline-block"
                                                title="Renvoyer l'email au client">
                                            <i class="fas fa-envelope"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $payments->links() }}
        </div>
    @endif
</div>

@csrf
<script>
function resendEmail(paymentId) {
    if (!confirm('Voulez-vous renvoyer l\'email de confirmation au client ?')) {
        return;
    }
    
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/receptionist/payments/${paymentId}/resend-email`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Email renvoyé avec succès !');
            location.reload();
        } else {
            alert('Erreur lors de l\'envoi de l\'email : ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de l\'envoi de l\'email');
    });
}
</script>
@endsection


