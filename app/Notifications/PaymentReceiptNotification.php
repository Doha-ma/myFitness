<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Payment;

class PaymentReceiptNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private Payment $payment,
        private string $receptionistName
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $payment = $this->payment;
        $receptionistName = $this->receptionistName;
        $member = $payment->member;

        return (new MailMessage)
            ->subject('🧾 Reçu de paiement - MyFitness Gym')
            ->greeting('Bonjour ' . $member->first_name . ' ' . $member->last_name . ',')
            ->line('Nous vous confirmons l\'enregistrement de votre paiement pour votre abonnement MyFitness.')
            ->line('Voici les détails de votre transaction:')
            ->line('**📋 Détails du paiement:**')
            ->line('• **Référence:** #' . str_pad((string) $payment->id, 6, '0', STR_PAD_LEFT))
            ->line('• **Montant:** ' . number_format($payment->amount, 2, ',', ' ') . ' DH')
            ->line('• **Date:** ' . $payment->payment_date->format('d/m/Y H:i'))
            ->line('• **Méthode:** ' . $this->getPaymentMethodLabel($payment->method))
            
            ->when($payment->subscriptionType, function ($message) use ($payment) {
                $message->line('**📦 Type d\'abonnement:**')
                    ->line('• **Nom:** ' . $payment->subscriptionType->name)
                    ->line('• **Durée:** ' . $payment->subscriptionType->duration_days . ' jours')
                    ->line('• **Prix:** ' . number_format($payment->subscriptionType->final_price, 2, ',', ' ') . ' DH');
            })

            ->when($payment->notes, function ($message) use ($payment) {
                $message->line('**📝 Notes:** ' . $payment->notes);
            })

            ->line('**📅 Période d\'abonnement:**')
            ->line('• **Date de début:** ' . $payment->payment_date->format('d/m/Y'))
            ->line('• **Date de fin:** ' . ($member->subscription_end_date ? $member->subscription_end_date->format('d/m/Y') : 'Non défini'))
            ->line('• **Statut actuel:** ' . $this->getStatusLabel($member->status))

            ->line('**👤 Enregistré par:** ' . $receptionistName)

            ->action('📄 Télécharger votre reçu PDF', route('receptionist.payments.invoice', $payment->id))

            ->line('Merci de votre confiance et à bientôt à MyFitness Gym! 💪')
            ->line('Pour toute question, n\'hésitez pas à nous contacter.')
            ->salutation('Cordialement,')
            ->salutation('L\'équipe MyFitness Gym');
    }

    private function getPaymentMethodLabel(string $method): string
    {
        return match($method) {
            'cash' => 'Espèces 💵',
            'card' => 'Carte bancaire 💳',
            'transfer' => 'Virement bancaire 🏦',
            default => ucfirst($method)
        };
    }

    private function getStatusLabel(string $status): string
    {
        return match($status) {
            'active' => 'Actif ✅',
            'inactive' => 'Inactif ⏸️',
            'expired' => 'Expiré ❌',
            default => ucfirst($status)
        };
    }
}
