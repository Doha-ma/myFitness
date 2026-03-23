<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Member;
use App\Models\Payment;
use App\Models\SubscriptionType;

echo "Test de mise à jour d'abonnement...\n\n";

// Trouver un membre avec des paiements
$member = Member::whereHas('payments')->with('payments.subscriptionType')->first();
if (!$member) {
    echo "Aucun membre avec des paiements trouvé\n";
    exit;
}

echo "Membre : " . $member->first_name . " " . $member->last_name . "\n";
echo "Date de fin actuelle : " . ($member->subscription_end_date ? $member->subscription_end_date->format('d/m/Y') : 'N/A') . "\n\n";

// Afficher les paiements
$payments = $member->payments()->with('subscriptionType')->orderBy('payment_date')->get();
foreach ($payments as $index => $payment) {
    echo "Paiement #" . ($index + 1) . ":\n";
    echo "  Date : " . $payment->payment_date->format('d/m/Y') . "\n";
    echo "  Type : " . ($payment->subscriptionType ? $payment->subscriptionType->name : 'N/A') . "\n";
    echo "  Durée : " . ($payment->subscriptionType ? $payment->subscriptionType->duration_days . ' jours' : 'N/A') . "\n";
    echo "  Montant : " . $payment->amount . " DH\n\n";
}

// Vérifier les types d'abonnement
echo "Types d'abonnement disponibles :\n";
$subscriptionTypes = SubscriptionType::all();
foreach ($subscriptionTypes as $type) {
    echo "  - " . $type->name . " : " . $type->duration_days . " jours (" . $type->final_price . " DH)\n";
}
