<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu #{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 24px;
            background: #F8FAFC;
            color: #1E293B;
        }
        .card {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
        }
        .flex {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
        }
        .badge {
            display: inline-block;
            padding: 6px 12px;
            background: #E0F2FE;
            color: #3B82F6;
            border-radius: 999px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.05em;
        }
        .title {
            font-size: 22px;
            margin: 0;
        }
        .text-muted { color: #64748B; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }
        th {
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: #F1F5F9;
            padding: 8px;
            color: #475569;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #E2E8F0;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #3B82F6;
            margin: 0;
        }
        .history-title {
            font-size: 14px;
            margin-bottom: 8px;
        }
        .logo {
            max-height: 48px;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    @php
        $subscription = $payment->subscriptionType;
        $member = $payment->member;
        $etab = $etablissement ?? $payment->etablissement;
    @endphp

    <div class="card">
        <div class="flex">
            <div>
                @if(!empty($etab?->logo) && file_exists(public_path($etab->logo)))
                    <img src="{{ public_path($etab->logo) }}" alt="Logo" class="logo">
                @endif
                <h1 class="title">{{ $etab->nom ?? config('app.name') }}</h1>
                <p class="text-muted">
                    {{ $etab->adresse ?? 'Adresse non renseignée' }}<br>
                    {{ $etab->telephone ?? '' }} {{ $etab->email ?? '' }}
                </p>
            </div>
            <div style="text-align:right;">
                <span class="badge">Reçu #{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</span>
                <p class="text-muted" style="margin-top:8px;">
                    Émis le {{ now()->translatedFormat('d F Y') }}<br>
                    Paiement du {{ $payment->payment_date?->translatedFormat('d F Y') }}
                </p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="flex">
            <div>
                <p class="text-muted" style="text-transform:uppercase; font-size:10px;">Client</p>
                <p style="margin:0; font-weight:bold;">
                    {{ $member?->full_name ?? 'Client inconnu' }}
                </p>
                <p class="text-muted" style="margin:2px 0;">
                    {{ $member?->email ?? '-' }} · {{ $member?->phone ?? '-' }}
                </p>
            </div>
            <div>
                <p class="text-muted" style="text-transform:uppercase; font-size:10px;">Paiement</p>
                <p style="margin:0;">ID: PAY-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</p>
                <p class="text-muted" style="margin:2px 0;">
                    Méthode: {{ ucfirst($payment->method ?? 'inconnue') }}<br>
                    Enregistré par: {{ $payment->receptionist->name ?? 'Système' }}
                </p>
            </div>
        </div>
    </div>

    <div class="card">
        <table>
            <tr>
                <th>Type d'abonnement</th>
                <th>Durée</th>
                <th>Notes</th>
                <th>Montant</th>
            </tr>
            <tr>
                <td>{{ $subscription->name ?? 'Paiement ponctuel' }}</td>
                <td>
                    @if($subscription)
                        {{ $subscription->duration_days }} jours
                    @else
                        -
                    @endif
                </td>
                <td>{{ $payment->notes ?? 'Aucune remarque' }}</td>
                <td>{{ number_format($payment->amount, 2, ',', ' ') }} DH</td>
            </tr>
        </table>
        <p class="amount">{{ number_format($payment->amount, 2, ',', ' ') }} DH</p>
    </div>

    @if(!empty($paymentHistory) && $paymentHistory->count() > 1)
        <div class="card">
            <p class="history-title">Historique des paiements</p>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Montant</th>
                    <th>Méthode</th>
                    <th>Type</th>
                </tr>
                @foreach($paymentHistory as $historic)
                    <tr>
                        <td>{{ $historic->payment_date?->format('d/m/Y') }}</td>
                        <td>{{ number_format($historic->amount, 2, ',', ' ') }} DH</td>
                        <td>{{ ucfirst($historic->method ?? 'n/a') }}</td>
                        <td>{{ $historic->subscriptionType->name ?? 'Libre' }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    @endif

    <div class="card">
        <p class="text-muted" style="margin:0;">
            Merci pour votre confiance. Conservez ce document comme preuve de paiement.<br>
            Contact: {{ $etab->email ?? config('mail.from.address') }} — {{ $etab->telephone ?? '' }}
        </p>
    </div>
</body>
</html>
