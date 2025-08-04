<?php

namespace App\Events;

use App\Models\CalonSantri; // <-- Import model
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StatusSantriDiperbarui
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The CalonSantri instance.
     *
     * @var \App\Models\CalonSantri
     */
    public $calonSantri; // <-- Buat properti publik

    /**
     * Create a new event instance.
     */
    public function __construct(CalonSantri $calonSantri) // <-- Terima model di constructor
    {
        $this->calonSantri = $calonSantri; // <-- Set properti
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
