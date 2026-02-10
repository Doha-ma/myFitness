<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\Member;

class NewMemberRegistered extends Notification
{
    use Queueable;

    public $member;
    public $receptionist;

    /**
     * Create a new notification instance.
     */
    public function __construct(Member $member, User $receptionist)
    {
        $this->member = $member;
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
            'title' => 'Nouveau membre inscrit',
            'message' => "{$this->receptionist->name} a inscrit un nouveau membre : {$this->member->full_name}",
            'action_type' => 'new_member',
            'performed_by' => $this->receptionist->name,
            'member_id' => $this->member->id,
            'member_name' => $this->member->full_name,
            'member_email' => $this->member->email,
            'receptionist_id' => $this->receptionist->id,
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
