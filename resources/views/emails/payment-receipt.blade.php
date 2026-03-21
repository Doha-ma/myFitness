@php
    $member = $payment->member;
    $etab = $etablissement;
@endphp

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Votre reçu de paiement</title>
</head>
<body style="font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #f5f7fb; padding: 32px; color: #1f2933;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 640px; margin: 0 auto; background: #ffffff; border-radius: 12px; box-shadow: 0 20px 45px rgba(15, 23, 42, 0.08); overflow: hidden;">
        <tr>
            <td style="padding: 32px; background: linear-gradient(120deg, #e0f2ff, #f8fbff); border-bottom: 1px solid #e5e7eb;">
                <h1 style="margin: 0; font-size: 22px; color: #0f172a;">
                    Merci de votre paiement, {{ $member->first_name }} !
                </h1>
                <p style="margin: 8px 0 0; color: #475569;">
                    Voici votre reçu pour le paiement #{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }} effectué le {{ $payment->payment_date->translatedFormat('d F Y') }}.
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 32px;">
                <p style="margin-top: 0; color: #475569;">
                    <strong>Montant :</strong> {{ number_format($payment->amount, 2, ',', ' ') }} DH<br>
                    <strong>Type d'abonnement :</strong> {{ $payment->subscriptionType->name ?? 'Paiement ponctuel' }}<br>
                    <strong>Mode de paiement :</strong> {{ ucfirst($payment->method) }}<br>
                    <strong>Enregistré par :</strong> {{ $payment->receptionist->name ?? config('app.name') }}
                </p>
                <p style="margin-top: 24px; color: #475569;">
                    Le reçu officiel est joint à cet e-mail au format PDF. Vous pouvez le conserver pour vos dossiers ou le partager avec votre entreprise.
                </p>
                <p style="margin-top: 24px; color: #475569;">
                    <strong>{{ $etab->name ?? config('app.name') }}</strong><br>
                    {{ $etab->address ?? '' }} {{ $etab->city ?? '' }}<br>
                    {{ $etab->phone ?? '' }} {{ $etab->email ?? '' }}
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 24px 32px; background: #f8fafc; text-align: center; color: #94a3b8; font-size: 12px;">
                Merci pour votre confiance et à très bientôt pour votre prochaine séance !
            </td>
        </tr>
    </table>
</body>
</html>
