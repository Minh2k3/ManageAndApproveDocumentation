<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Models\User;
use App\Models\Document;
use App\Models\Notification;

class SaveDraft implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $notification;
    public $document_id;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, Notification $notification, $document_id)
    {
        $this->user = $user;
        $this->notification = $notification;
        $this->document_id = $document_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new Channel('user.' . $this->user->id);  // Kênh private cho người dùng
    }

    public function broadcastAs()
    {
        return 'new-notification';  // Tên sự kiện phát
    }
}
