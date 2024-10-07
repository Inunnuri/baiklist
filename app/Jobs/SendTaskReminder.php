<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use App\Notifications\TaskReminderNotification;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTaskReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $task;

    /**
     * Create a new job instance.
     */
    public function __construct($task)
    {
        $this->task = $task;
    }

    /**
     * Execute the job.
     */
    // membuat notifikasi sesuai task , jika due_date hari ini maka tidak dibuat kan notifikasi
    public function handle(): void
    {
        $user = User::find($this->task->user_id); // Ambil pengguna terkait dengan tugas ini
    
        // Cek jika due_date adalah hari ini
        if ($this->task->due_date->isToday()) {
            return; // Tidak perlu membuat notifikasi
        }
    
        if ($user) {
            $user->notify(new TaskReminderNotification($this->task));
        } else {
            // Log jika pengguna tidak ditemukan
            Log::error('User not found for task ID: ' . $this->task->id);
        }
    }
    

}
