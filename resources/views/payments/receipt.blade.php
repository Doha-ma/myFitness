<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu N°{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</title>
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
        }
        
        .receipt-container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 2px solid #111;
            padding-bottom: 20px;
        }
        
        .logo-section {
            flex: 1;
        }
        
        .logo-section h1 {
            font-size: 24px;
            font-weight: bold;
            color: #111;
            margin-bottom: 5px;
        }
        
        .logo-section p {
            font-size: 12px;
            color: #888;
            margin: 0;
        }
        
        .receipt-info {
            text-align: right;
            flex: 1;
        }
        
        .receipt-number {
            font-size: 18px;
            font-weight: bold;
            color: #111;
            margin-bottom: 5px;
        }
        
        .receipt-date {
            font-size: 14px;
            color: #444;
        }
        
        .client-info {
            margin: 30px 0;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 4px;
        }
        
        .client-info h3 {
            font-size: 16px;
            font-weight: bold;
            color: #111;
            margin-bottom: 10px;
        }
        
        .client-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            font-size: 14px;
        }
        
        .client-details span {
            color: #444;
        }
        
        .client-details strong {
            color: #111;
        }
        
        .services-section {
            margin: 30px 0;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #111;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px dotted #888;
        }
        
        .services-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .services-table th {
            text-align: left;
            padding: 12px;
            background: #f5f5f5;
            font-weight: bold;
            color: #111;
            border-bottom: 2px solid #111;
        }
        
        .services-table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            color: #444;
        }
        
        .services-table .amount {
            text-align: right;
            font-weight: bold;
        }
        
        .total-section {
            margin: 20px 0;
            text-align: right;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            font-size: 14px;
        }
        
        .total-row.subtotal {
            border-bottom: 1px dotted #888;
        }
        
        .total-row.grand-total {
            font-size: 20px;
            font-weight: bold;
            color: #111;
            border-top: 2px solid #111;
            padding-top: 15px;
            margin-top: 10px;
        }
        
        .payment-info {
            margin: 30px 0;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 4px;
        }
        
        .payment-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            font-size: 14px;
        }
        
        .payment-details strong {
            color: #111;
        }
        
        .payment-details span {
            color: #444;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px dotted #888;
            text-align: center;
            font-size: 12px;
            color: #888;
        }
        
        .footer p {
            margin: 5px 0;
        }
        
        .footer .signature {
            margin-top: 30px;
            text-align: left;
        }
        
        .signature-line {
            border-bottom: 1px solid #111;
            width: 250px;
            margin-bottom: 5px;
        }
        
        .signature-text {
            font-size: 12px;
            color: #444;
        }
        
        /* Print styles */
        @media print {
            body {
                background: white;
            }
            
            .receipt-container {
                box-shadow: none;
                margin: 0;
                padding: 20px;
                max-width: 100%;
            }
            
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="header">
            <div class="logo-section">
                <h1>MyFitness Gym</h1>
                <p>Salle de sport professionnelle</p>
                <p>123 Avenue Mohammed V, Casablanca</p>
                <p>Tél: +212 5XX-XXXXXX</p>
                <p>Email: contact@myfitness.ma</p>
            </div>
            <div class="receipt-info">
                <div class="receipt-number">Reçu N°{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="receipt-date">
                    Date: {{ $payment->payment_date->format('d/m/Y') }}<br>
                    Heure: {{ $payment->payment_date->format('H:i') }}
                </div>
            </div>
        </div>

        <!-- Client Information -->
        <div class="client-info">
            <h3>INFORMATIONS CLIENT</h3>
            <div class="client-details">
                <div><strong>Nom:</strong> <span>{{ $payment->member->first_name }} {{ $payment->member->last_name }}</span></div>
                <div><strong>Email:</strong> <span>{{ $payment->member->email }}</span></div>
                <div><strong>Téléphone:</strong> <span>{{ $payment->member->phone }}</span></div>
                <div><strong>Statut:</strong> <span>{{ $payment->member->status == 'active' ? 'Actif' : 'Inactif' }}</span></div>
            </div>
        </div>

        <!-- Services Section -->
        <div class="services-section">
            <h3 class="section-title">DÉTAILS DU PAIEMENT</h3>
            <table class="services-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Période</th>
                        <th class="amount">Montant</th>
                    </tr>
                </thead>
                <tbody>
                    @if($payment->subscriptionType)
                    <tr>
                        <td>{{ $payment->subscriptionType->name }}</td>
                        <td>{{ $payment->subscriptionType->duration_days }} jours</td>
                        <td class="amount">{{ number_format($payment->subscriptionType->final_price, 2, ',', ' ') }} DH</td>
                    </tr>
                    @else
                    <tr>
                        <td>Paiement manuel</td>
                        <td>-</td>
                        <td class="amount">{{ number_format($payment->amount, 2, ',', ' ') }} DH</td>
                    </tr>
                    @endif
                </tbody>
            </table>

            <!-- Total Section -->
            <div class="total-section">
                @if($payment->subscriptionType && $payment->subscriptionType->discount_value > 0)
                <div class="total-row">
                    <span>Sous-total:</span>
                    <span>{{ number_format($payment->subscriptionType->base_price, 2, ',', ' ') }} DH</span>
                </div>
                <div class="total-row">
                    <span>Remise ({{ $payment->subscriptionType->discount_type == 'percentage' ? $payment->subscriptionType->discount_value . '%' : number_format($payment->subscriptionType->discount_value, 2, ',', ' ') . ' DH' }}):</span>
                    <span>-{{ number_format($payment->subscriptionType->base_price - $payment->subscriptionType->final_price, 2, ',', ' ') }} DH</span>
                </div>
                @endif
                <div class="total-row subtotal">
                    <span>Montant HT:</span>
                    <span>{{ number_format($payment->amount, 2, ',', ' ') }} DH</span>
                </div>
                <div class="total-row">
                    <span>TVA (20%):</span>
                    <span>{{ number_format($payment->amount * 0.2, 2, ',', ' ') }} DH</span>
                </div>
                <div class="total-row grand-total">
                    <span>TOTAL:</span>
                    <span>{{ number_format($payment->amount * 1.2, 2, ',', ' ') }} DH</span>
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="payment-info">
            <h3 class="section-title">INFORMATIONS DE PAIEMENT</h3>
            <div class="payment-details">
                <div><strong>Mode de paiement:</strong> <span>{{ $payment->method == 'cash' ? 'Espèces' : ($payment->method == 'card' ? 'Carte bancaire' : 'Virement bancaire') }}</span></div>
                <div><strong>Date de paiement:</strong> <span>{{ $payment->payment_date->format('d/m/Y H:i') }}</span></div>
                <div><strong>Enregistré par:</strong> <span>{{ $payment->receptionist->name }}</span></div>
                <div><strong>Référence:</strong> <span>RCPT-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</span></div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Ce reçu fait foi de paiement</strong></p>
            <p>Merci de votre confiance dans MyFitness Gym</p>
            
            <div class="signature">
                <div class="signature-line"></div>
                <div class="signature-text">Signature du réceptionniste</div>
            </div>
            
            <p style="margin-top: 30px;">
                <strong>MyFitness Gym</strong><br>
                123 Avenue Mohammed V, Casablanca<br>
                Tél: +212 5XX-XXXXXX | Email: contact@myfitness.ma<br>
                www.myfitness.ma
            </p>
        </div>
    </div>
</body>
</html>
