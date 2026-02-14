<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CourseApproved extends Notification implements ShouldQueue
{
    use Queueable;

    public $course;

    public function __construct($course)
    {
        $this->course = $course;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Votre cours a ete approuve')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre cours "' . $this->course->name . '" a ete approuve et est maintenant visible pour les membres.')
            ->line('Les membres peuvent maintenant s\'inscrire a ce cours.')
            ->action('Voir le cours', url('/coach/classes/' . $this->course->id))
            ->line('Merci de contribuer a notre salle de sport!');
    }

    public function toArray($notifiable)
    {
        return [
            'course_id' => $this->course->id,
            'course_name' => $this->course->name,
            'message' => 'Votre cours "' . $this->course->name . '" a ete approuve.',
            'type' => 'course_approved'
        ];
    }
}

