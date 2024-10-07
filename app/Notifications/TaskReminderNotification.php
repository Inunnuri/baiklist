<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TaskReminderNotification extends Notification
{
    use Queueable;
    protected $task;

    /**
     * Create a new notification instance.
     */
    public function __construct($task)
    {
        $this->task = $task;
    }

    /**
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
        ->subject('Task Reminder')
        ->line('You have a task that is due soon.')
        ->action('View Task', route('tasks.index', $this->task->id))
        ->line('Thank you for using our application!');
    }

    public function toDatabase($notifiable)
    {
        Log::info('Creating database notification for Task ID: ' . $this->task->id);
        return [
            'task_id' => $this->task->id,
            'task_owner_id' => $this->task->user_id,
            'task_title' => $this->task->title,
            'reminder_at' => $this->task->reminder_at,
            'task_due' => $this->task->due_date,
        ];
    }

    

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'task_id' => $this->task->id,
            'task_owner_id' => $this->task->user_id,
            'task_title' => $this->task->title,
            'reminder_at' => $this->task->reminder_at,
            'task_due' => $this->task->due_date,
        ];
    }
}
