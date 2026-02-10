<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\ClassModel;

class NewCourseCreated extends Notification
{
    use Queueable;

    public $course;
    public $coach;

    /**
     * Create a new notification instance.
     */
    public function __construct(ClassModel $course, User $coach)
    {
        $this->course = $course;
        $this->coach = $coach;
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
            'title' => 'Nouveau cours créé',
            'message' => "{$this->coach->name} a créé un nouveau cours : {$this->course->name}",
            'action_type' => 'new_course',
            'performed_by' => $this->coach->name,
            'course_id' => $this->course->id,
            'course_name' => $this->course->name,
            'course_description' => $this->course->description,
            'coach_id' => $this->coach->id,
            'status' => $this->course->status,
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
