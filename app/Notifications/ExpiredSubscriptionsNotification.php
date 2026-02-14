<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ExpiredSubscriptionsNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly int $expiredCount
    ) {
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
            'title' => 'Abonnements expires',
            'message' => "Il y a {$this->expiredCount} membre(s) avec un abonnement expire.",
            'action_type' => 'expired_subscriptions',
            'performed_by' => 'Systeme',
            'expired_count' => $this->expiredCount,
            'action_url' => route('admin.members.index', ['subscription_status' => 'expired']),
            'date' => now()->toDateString(),
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];
    }
}

