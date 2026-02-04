<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rappel d'expiration d'abonnement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
            text-align: center;
            margin: -30px -30px 20px -30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .highlight {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 12px;
        }
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">{{ config('app.name') }}</div>
            <h1>Rappel d'abonnement</h1>
        </div>

        <p>Bonjour <strong>{{ $member->full_name }}</strong>,</p>

        @if($daysUntilExpiry <= 0)
            <div class="highlight">
                <h3>⚠️ Votre abonnement a expiré!</h3>
                <p>Votre abonnement a expiré le {{ $expiryDate->format('d/m/Y') }}.</p>
            </div>
        @else
            <div class="highlight">
                <h3>📅 Votre abonnement expire bientôt!</h3>
                <p>Votre abonnement expire dans <strong>{{ $daysUntilExpiry }} jour(s)</strong>, le {{ $expiryDate->format('d/m/Y') }}.</p>
            </div>
        @endif

        <div class="info-box">
            <h4>Informations sur votre abonnement:</h4>
            <ul>
                <li><strong>Type d'abonnement:</strong> {{ $subscriptionType->name }}</li>
                <li><strong>Durée:</strong> {{ $subscriptionType->duration_days }} jours</li>
                <li><strong>Dernier paiement:</strong> {{ $lastPayment->payment_date->format('d/m/Y') }}</li>
                <li><strong>Montant payé:</strong> {{ number_format($lastPayment->amount, 2) }} DH</li>
                <li><strong>Prix de renouvellement:</strong> {{ $renewalPrice }}</li>
            </ul>
        </div>

        <p>Pour continuer à bénéficier de nos services sans interruption, nous vous invitons à renouveler votre abonnement dès que possible.</p>

        <p>Vous pouvez vous rendre à la réception de notre club ou nous contacter pour effectuer votre renouvellement.</p>

        <div style="text-align: center;">
            <a href="#" class="btn">Renouveler mon abonnement</a>
        </div>

        <p>Si vous avez des questions ou besoin d'assistance, n'hésitez pas à nous contacter:</p>
        <ul>
            <li>Par téléphone: {{ config('app.phone', 'Non spécifié') }}</li>
            <li>Par email: {{ config('app.email', 'Non spécifié') }}</li>
        </ul>

        <div class="footer">
            <p>Cet email a été envoyé automatiquement par le système de gestion de {{ config('app.name') }}.</p>
            <p>Si vous ne souhaitez plus recevoir ces rappels, veuillez nous contacter.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
