<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #{{ $payment->id }}</title>
    <style>
        /* PDF-safe styles compatible with DomPDF */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        
        .header {
            background: linear-gradient(135deg, #FF6B35 0%, #004E89 100%);
            color: white;
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .info-section {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        
        .info-left, .info-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 20px;
        }
        
        .info-right {
            text-align: right;
        }
        
        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        
        .info-box h3 {
            font-size: 14px;
            color: #FF6B35;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        
        .info-box p {
            margin: 3px 0;
            font-size: 12px;
        }
        
        .payment-details {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .payment-details h2 {
            color: #FF6B35;
            font-size: 18px;
            margin-bottom: 20px;
            border-bottom: 2px solid #FF6B35;
            padding-bottom: 10px;
        }
        
        .detail-row {
            display: table;
            width: 100%;
            margin-bottom: 12px;
        }
        
        .detail-label {
            display: table-cell;
            width: 40%;
            font-weight: bold;
            color: #555;
        }
        
        .detail-value {
            display: table-cell;
            width: 60%;
            color: #333;
        }
        
        .amount-box {
            background: linear-gradient(135deg, #FF6B35 0%, #F7931E 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-top: 20px;
        }
        
        .amount-box .label {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 5px;
        }
        
        .amount-box .value {
            font-size: 32px;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        
        .method-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .method-cash {
            background: #10B981;
            color: white;
        }
        
        .method-card {
            background: #3B82F6;
            color: white;
        }
        
        .method-transfer {
            background: #8B5CF6;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1> myFitness</h1>
        <p>Centre de Fitness et Bien-être</p>
    </div>
    
    <div class="info-section">
        <div class="info-left">
            <div class="info-box">
                <h3>Informations du Membre</h3>
                <p><strong>Nom:</strong> {{ $payment->member->first_name }} {{ $payment->member->last_name }}</p>
                <p><strong>Email:</strong> {{ $payment->member->email }}</p>
                <p><strong>Téléphone:</strong> {{ $payment->member->phone }}</p>
                @if($payment->member->address)
                    <p><strong>Adresse:</strong> {{ $payment->member->address }}</p>
                @endif
            </div>
        </div>
        
        <div class="info-right">
            <div class="info-box">
                <h3>Informations de la Facture</h3>
                <p><strong>N° Facture:</strong> #{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</p>
                <p><strong>Référence:</strong> PAY-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</p>
                <p><strong>Date:</strong> {{ $payment->payment_date->format('d/m/Y') }}</p>
                <p><strong>Heure:</strong> {{ $payment->created_at->format('H:i') }}</p>
                <p><strong>Enregistré par:</strong> {{ $payment->receptionist->name }}</p>
            </div>
        </div>
    </div>
    
    <div class="payment-details">
        <h2>Détails du Paiement</h2>
        
        <div class="detail-row">
            <div class="detail-label">Montant:</div>
            <div class="detail-value">{{ number_format($payment->amount, 2, ',', ' ') }} €</div>
        </div>
        
        <div class="detail-row">
            <div class="detail-label">Méthode de paiement:</div>
            <div class="detail-value">
                @if($payment->method == 'cash')
                    <span class="method-badge method-cash"> Espèces</span>
                @elseif($payment->method == 'card')
                    <span class="method-badge method-card"> Carte</span>
                @else
                    <span class="method-badge method-transfer"> Virement</span>
                @endif
            </div>
        </div>
        
        <div class="detail-row">
            <div class="detail-label">Date de paiement:</div>
            <div class="detail-value">{{ $payment->payment_date->format('d/m/Y') }}</div>
        </div>
        
        @if($payment->notes)
            <div class="detail-row">
                <div class="detail-label">Notes:</div>
                <div class="detail-value">{{ $payment->notes }}</div>
            </div>
        @endif
        
        <div class="amount-box">
            <div class="label">Montant Total</div>
            <div class="value">{{ number_format($payment->amount, 2, ',', ' ') }} €</div>
        </div>
    </div>
    
    <div class="footer">
        <p><strong>myFitness</strong> - Centre de Fitness et Bien-être</p>
        <p>Merci pour votre confiance !</p>
        <p>Cette facture a été générée automatiquement le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>
</body>
</html>
