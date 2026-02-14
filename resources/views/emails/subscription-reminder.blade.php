<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rappel d'abonnement expire - MyFitness</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f4f6f8;
            font-family: Arial, sans-serif;
            color: #1f2937;
        }

        .wrapper {
            max-width: 640px;
            margin: 24px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
        }

        .header {
            background: linear-gradient(135deg, #1a1a2e 0%, #004e89 100%);
            color: #ffffff;
            padding: 24px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 0.03em;
        }

        .content {
            padding: 24px;
            line-height: 1.6;
        }

        .alert {
            margin: 16px 0;
            border: 1px solid #fecaca;
            background: #fff1f2;
            color: #991b1b;
            padding: 14px;
            border-radius: 8px;
        }

        .summary {
            margin: 18px 0;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 14px;
            background: #f9fafb;
        }

        .summary p {
            margin: 6px 0;
        }

        .footer {
            border-top: 1px solid #e5e7eb;
            background: #f9fafb;
            padding: 16px 24px;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>MyFitness</h1>
            <p style="margin: 8px 0 0 0;">Rappel de paiement d'abonnement</p>
        </div>

        <div class="content">
            <p>Bonjour <strong>{{ $member->full_name }}</strong>,</p>

            <p>
                Nous vous informons que votre abonnement est expire depuis le
                <strong>{{ $expiryDate->format('d/m/Y') }}</strong>.
            </p>

            <div class="alert">
                Merci d'effectuer votre reglement afin de reactiver votre acces aux services MyFitness.
            </div>

            <div class="summary">
                <p><strong>Type d'abonnement :</strong> {{ $subscriptionType->name }}</p>
                <p><strong>Date d'expiration :</strong> {{ $expiryDate->format('d/m/Y') }}</p>
                <p><strong>Dernier paiement :</strong> {{ $lastPayment->payment_date->format('d/m/Y') }}</p>
                <p><strong>Montant du renouvellement :</strong> {{ $renewalPrice }}</p>
            </div>

            <p>
                Pour toute assistance, vous pouvez vous presenter a l'accueil ou repondre directement a cet email.
            </p>

            <p>Cordialement,<br><strong>Equipe MyFitness</strong></p>
        </div>

        <div class="footer">
            Cet email est envoye automatiquement par MyFitness.
        </div>
    </div>
</body>
</html>

