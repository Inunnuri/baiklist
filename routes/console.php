<?php

//  jika kamu membuat perintah kustom untuk menjalankan tugas tertentu, kamu bisa mendefinisikannya di sini.
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('schedule', function (Schedule $schedule) {
    $schedule->command('reminders:send-daily')->daily();
});