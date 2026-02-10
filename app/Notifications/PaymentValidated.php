<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\Payment;

class PaymentValidated extends Notification
{
    use Queueable;

    public $payment;
    public $receptionist;

    /**
     * Create a new notification instance.
     */
    public function __construct(Payment $payment, User $receptionist)
    {
        $this->payment = $payment;
        $this->receptionist = $receptionist;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Nouveau paiement validé',
            'message' => "{$this->receptionist->name} a validé un paiement de {$this->payment->amount} DH pour {$this->payment->member->full_name}",
            'action_type' => 'payment',
            'performed_by' => $this->receptionist->name,
            'payment_id' => $this->payment->id,
            'payment_amount' => $this->payment->amount,
            'payment_method' => $this->payment->method,
            'member_name' => $this->payment->member->full_name,
            'receptionist_id' => $this->receptionist->id,
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
