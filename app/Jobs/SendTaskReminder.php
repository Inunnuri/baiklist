<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\TaskReminderNotification;

class SendTaskReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
// Dispatchable: Trait ini memungkinkan job dipanggil menggunakan fungsi dispatch().
// InteractsWithQueue: untuk berinteraksi dengan queue
// Queueable: Membuat job dapat dikirim ke queue
// SerializesModels: Trait ini memungkinkan model yang terkait dengan job (seperti $task) diserialisasikan saat job dikirimkan ke antrean.
    private $task;
//Variabel ini untuk menyimpan informasi tugas yang akan diproses oleh job ini. Tugas tersebut akan dilewatkan ke job melalui konstruktor.
    public function __construct($task)
    {
        $this->task = $task;
    }
    //konstruktor yang dijalankan ketika job dibuat. Pada saat job dibuat, data tugas ($task) diterima sebagai parameter dan disimpan dalam variabel privat $task untuk digunakan di metode lain.

    
    //handle(): Fungsi ini adalah inti dari job, di mana semua logika eksekusi diletakkan. Fungsi ini dipanggil ketika job dijalankan, baik secara langsung atau melalui antrean.
    //void: Menandakan bahwa fungsi ini tidak mengembalikan nilai apapun.
    public function handle(): void
    {
        $user = User::find($this->task->user_id); // Ambil pengguna terkait dengan tugas ini
    
        //jika due_date hari ini maka tidak dibuat kan notifikasi
        //Cek jika due_date adalah hari ini, pastikan mengonversi due_date yang berupa string menjadi objek Carbon.
        if (Carbon::parse($this->task->due_date)->isToday()) {
            return; // Tidak perlu membuat notifikasi
        }
    
        if ($user) {
            $user->notify(new TaskReminderNotification($this->task));
            //Jika pengguna ditemukan, notifikasi pengingat tugas (TaskReminderNotification) akan dikirim ke pengguna dengan metode notify().
        } else {
            // Log jika pengguna tidak ditemukan
            Log::error('User not found for task ID: ' . $this->task->id);
        }
    }
    // done

}
