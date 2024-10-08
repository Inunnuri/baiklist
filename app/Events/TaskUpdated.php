<?php

namespace App\Events;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $task;

    /**
     * Create a new event instance.
     */
    public function __construct($task)
    {
        $this->task = $task;
        // ketika event TaskUpdated dipicu, ia akan membawa informasi tentang task yang bersangkutan.
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    // Metode ini menentukan saluran mana yang akan digunakan untuk menyiarkan event ini.
    public function broadcastOn(): array
    {
        //Mengembalikan array yang berisi objek PrivateChannel. Dalam hal ini, event ini akan disiarkan ke saluran pribadi bernama tasks.
        return [
            new PrivateChannel('tasks'),
        ];
    }
}
