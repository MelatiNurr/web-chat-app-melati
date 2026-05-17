<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message;

    /**
     * Buat event baru. Data pesan langsung dibawa sebagai "payload".
     */
    public function __construct(Message $message)
    {
        $this->message = $message->load('sender');
    }

    /**
     * Tentukan ke channel mana event ini akan dikirim.
     * Jika ada group_id → kirim ke channel grup
     * Jika tidak (private) → kirim ke channel antara 2 user
     */
    public function broadcastOn(): array
    {
        if ($this->message->group_id) {
            return [
                new PrivateChannel('chat.group.' . $this->message->group_id),
            ];
        }

        // Selalu urutkan ID dari kecil ke besar agar nama channel konsisten
        $ids = [$this->message->sender_id, $this->message->receiver_id];
        sort($ids);

        return [
            new PrivateChannel('chat.private.' . $ids[0] . '.' . $ids[1]),
        ];
    }

    /**
     * Data yang dikirim ke browser bersama event ini
     */
    public function broadcastWith(): array
    {
        return [
            'id'          => $this->message->id,
            'message'     => $this->message->message,
            'sender_id'   => $this->message->sender_id,
            'receiver_id' => $this->message->receiver_id,
            'group_id'    => $this->message->group_id,
            'sender_name' => $this->message->sender->name,
            'created_at'  => $this->message->created_at->format('H:i'),
        ];
    }
}
