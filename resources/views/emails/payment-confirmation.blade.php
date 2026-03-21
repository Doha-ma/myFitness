<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de paiement - MyFitness Gym</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #111;
            line-height: 1.6;
            background: #f5f5f5;
            padding: 20px;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .header {
            background: #111;
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .content {
            padding: 30px;
        }
        
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #111;
        }
        
        .message {
            font-size: 16px;
            color: #444;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .receipt-section {
            background: #f9f9f9;
            border-radius: 6px;
            padding: 20px;
            margin: 30px 0;
        }
        
        .receipt-title {
            font-size: 16px;
            font-weight: bold;
            color: #111;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .receipt-table td {
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
        }
        
        .receipt-table td:first-child {
            color: #666;
            font-weight: 500;
        }
        
        .receipt-table td:last-child {
            text-align: right;
            font-weight: bold;
            color: #111;
        }
        
        .total-row {
            background: #111;
            color: white;
        }
        
        .total-row td {
            border-bottom: none;
            padding: 15px 10px;
            font-size: 16px;
        }
        
        .receipt-embedded {
            margin: 30px 0;
            border: 1px solid #ddd;
            border-radius: 6px;
            overflow: hidden;
        }
        
        .receipt-embedded-header {
            background: #f5f5f5;
            padding: 15px;
            text-align: center;
            font-weight: bold;
            color: #111;
            border-bottom: 1px solid #ddd;
        }
        
        .receipt-embedded-content {
            padding: 20px;
            font-size: 12px;
            color: #444;
        }
        
        .footer {
            background: #f9f9f9;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #ddd;
        }
        
        .footer p {
            font-size: 14px;
            color: #666;
            margin: 5px 0;
        }
        
        .footer .disclaimer {
            font-size: 12px;
            color: #888;
            margin-top: 15px;
            font-style: italic;
        }
        
        .button {
            display: inline-block;
            background: #111;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        
        .button:hover {
            background: #333;
        }
        
        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }
            
            .content, .header, .footer {
                padding: 20px;
            }
            
            .receipt-table td {
                padding: 8px 5px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>MyFitness Gym</h1>
            <p>Salle de sport professionnelle</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Bonjour {{ $member->first_name }} {{ $member->last_name }},
            </div>
            
            <div class="message">
                Merci pour votre paiement. Nous vous confirmons que votre transaction a été enregistrée avec succès dans notre système.
            </div>

            <!-- Receipt Summary -->
            <div class="receipt-section">
                <div class="receipt-title">RÉCAPITULATIF DU PAIEMENT</div>
                <table class="receipt-table">
                    <tr>
                        <td>Référence du reçu</td>
                        <td>N°{{ $receiptNumber }}</td>
                    </tr>
                    <tr>
                        <td>Date du paiement</td>
                        <td>{{ $payment->payment_date->format('d/m/Y H:i') }}</td>
                    </tr>
                    @if($payment->subscriptionType)
                    <tr>
                        <td>Type d'abonnement</td>
                        <td>{{ $payment->subscriptionType->name }}</td>
                    </tr>
                    <tr>
                        <td>Durée</td>
                        <td>{{ $payment->subscriptionType->duration_days }} jours</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Montant total payé</td>
                        <td>{{ number_format($payment->amount, 2, ',', ' ') }} DH</td>
                    </tr>
                    <tr>
                        <td>Mode de paiement</td>
                        <td>{{ $payment->method == 'cash' ? 'Espèces' : ($payment->method == 'card' ? 'Carte bancaire' : 'Virement bancaire') }}</td>
                    </tr>
                    <tr class="total-row">
                        <td>TOTAL TTC</td>
                        <td>{{ number_format($payment->amount * 1.2, 2, ',', ' ') }} DH</td>
                    </tr>
                </table>
            </div>

            <!-- Action Button -->
            <div style="text-align: center;">
                <a href="{{ $receiptUrl }}" class="button">
                    Voir le reçu complet
                </a>
            </div>

            <!-- Embedded Receipt (simplified version) -->
            <div class="receipt-embedded">
                <div class="receipt-embedded-header">
                    Reçu N°{{ $receiptNumber }} - Aperçu
                </div>
                <div class="receipt-embedded-content">
                    <strong>Client:</strong> {{ $member->first_name }} {{ $member->last_name }}<br>
                    <strong>Email:</strong> {{ $member->email }}<br>
                    <strong>Date:</strong> {{ $payment->payment_date->format('d/m/Y H:i') }}<br>
                    <strong>Montant:</strong> {{ number_format($payment->amount, 2, ',', ' ') }} DH<br>
                    <strong>Mode:</strong> {{ $payment->method == 'cash' ? 'Espèces' : ($payment->method == 'card' ? 'Carte' : 'Virement') }}<br>
                    <strong>Référence:</strong> RCPT-{{ $receiptNumber }}
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>MyFitness Gym</strong></p>
            <p>123 Avenue Mohammed V, Casablanca</p>
            <p>Tél: +212 5XX-XXXXXX | Email: contact@myfitness.ma</p>
            <p>www.myfitness.ma</p>
            
            <p class="disclaimer">
                Ce reçu fait foi de paiement et doit être conservé pour vos records.
                Pour toute question concernant votre paiement, n'hésitez pas à nous contacter.
            </p>
        </div>
    </div>
</body>
</html>
