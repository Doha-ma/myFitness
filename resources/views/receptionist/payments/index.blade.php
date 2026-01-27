@extends('layouts.app')

@section('title', 'Gestion Paiements')

@section('sidebar')
    <a href="{{ route('receptionist.dashboard') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition">📊 Dashboard</a>
    <a href="{{ route('receptionist.members.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition">👥 Membres</a>
    <a href="{{ route('receptionist.payments.index') }}" class="block px-4 py-3 rounded hover:bg-white/10 transition bg-white/10">💰 Paiements</a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="block w-full text-left px-4 py-3 rounded hover:bg-white/10 transition">🚪 Déconnexion</button>
    </form>
@endsection

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <h2 class="text-4xl font-bold text-white">Gestion des Paiements</h2>
        <a href="{{ route('receptionist.payments.create') }}" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">
            ➕ Enregistrer un Paiement
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
        <p class="text-gray-500 text-center py-8">Aucun paiement enregistré</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-3 px-4">Membre</th>
                        <th class="text-left py-3 px-4">Montant</th>
                        <th class="text-left py-3 px-4">Date</th>
                        <th class="text-left py-3 px-4">Méthode</th>
                        <th class="text-left py-3 px-4">Enregistré par</th>
                        <th class="text-left py-3 px-4">Notes</th>
                        <th class="text-left py-3 px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4 font-semibold">{{ $payment->member->full_name ?? 'N/A' }}</td>
                            <td class="py-3 px-4">
                                <span class="font-bold text-green-600">{{ number_format($payment->amount, 2) }} €</span>
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
                                <a href="{{ route('receptionist.payments.invoice', $payment) }}" 
                                   class="btn-primary text-white px-4 py-2 rounded-lg text-sm font-semibold hover:shadow-lg transition inline-block"
                                   target="_blank"
                                   title="Télécharger la facture PDF">
                                    📄 PDF
                                </a>
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
@endsection
