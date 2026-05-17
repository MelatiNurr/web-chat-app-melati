<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Group;

// Channel default Laravel (notifikasi per-user)
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Channel untuk private chat antara 2 user
// Hanya 2 user yang terlibat yang boleh mendengarkan
Broadcast::channel('chat.private.{user1Id}.{user2Id}', function ($user, $user1Id, $user2Id) {
    return (int) $user->id === (int) $user1Id || (int) $user->id === (int) $user2Id;
});

// Channel untuk group chat
// Hanya anggota grup yang boleh mendengarkan
Broadcast::channel('chat.group.{groupId}', function ($user, $groupId) {
    $group = Group::find($groupId);
    return $group && $group->users->contains($user->id);
});

// Presence channel untuk tracking siapa yang online
// Mengembalikan data user yang sedang online
Broadcast::channel('online', function ($user) {
    return ['id' => $user->id, 'name' => $user->name];
});
