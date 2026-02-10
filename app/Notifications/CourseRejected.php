<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CourseRejected extends Notification implements ShouldQueue
{
    use Queueable;

    public $course;
    public $rejectionReason;

    public function __construct($course, $rejectionReason = null)
    {
        $this->course = $course;
        $this->rejectionReason = $rejectionReason;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject('Votre cours a été rejeté')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre cours "' . $this->course->name . '" a été rejeté.');

        if ($this->rejectionReason) {
            $message->line('Raison du rejet: ' . $this->rejectionReason);
        }

        $message->line('Vous pouvez modifier ce cours et le soumettre à nouveau pour validation.')
            ->action('Modifier le cours', url('/coach/classes/' . $this->course->id . '/edit'))
            ->line('Merci de contribuer à notre salle de sport!');

        return $message;
    }

    public function toArray($notifiable)
    {
        return [
            'course_id' => $this->course->id,
            'course_name' => $this->course->name,
            'rejection_reason' => $this->rejectionReason,
            'message' => 'Votre cours "' . $this->course->name . '" a été rejeté.',
            'type' => 'course_rejected'
        ];
    }
}
