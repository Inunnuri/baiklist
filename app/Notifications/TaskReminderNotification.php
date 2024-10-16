<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskReminderNotification extends Notification
{
    use Queueable;// Mengaktifkan kemampuan queue pada notifikasi

    protected $task;//deklarasi variabel

    /**
     * Create a new notification instance.
     */
    public function __construct($task)
    {
        $this->task = $task;// Menyimpan data task yang diterima saat pembuatan notifikasi
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
        ->line('You have a task that is due soon.')//baris teks di dalam isi email
        ->action('View Task', route('tasks.index', $this->task->id))//'View Task' nama button
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
        return [];
    }
}
// done
