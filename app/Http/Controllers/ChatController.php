<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Group;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Halaman utama chat (akan menampilkan daftar kontak & grup)
     */
    public function index()
    {
        $users  = User::where('id', '!=', Auth::id())->get();
        $groups = Auth::user()->groups;

        return view('chat.index', compact('users', 'groups'));
    }

    /**
     * Halaman private chat dengan satu user
     */
    public function privateChat(User $user)
    {
        $authId    = Auth::id();
        $receiverId = $user->id;

        // Ambil semua pesan antara user yang login dengan user tujuan
        $messages = Message::where(function ($query) use ($authId, $receiverId) {
            $query->where('sender_id', $authId)->where('receiver_id', $receiverId);
        })->orWhere(function ($query) use ($authId, $receiverId) {
            $query->where('sender_id', $receiverId)->where('receiver_id', $authId);
        })->with('sender')->orderBy('created_at', 'asc')->get();

        // Buat nama channel yang konsisten (ID kecil dulu)
        $ids = [$authId, $receiverId];
        sort($ids);
        $channelName = 'chat.private.' . $ids[0] . '.' . $ids[1];

        $users  = User::where('id', '!=', $authId)->get();
        $groups = Auth::user()->groups;

        return view('chat.index', compact('messages', 'user', 'users', 'groups', 'channelName'));
    }

    /**
     * Halaman group chat
     */
    public function groupChat(Group $group)
    {
        // Cek apakah user ini anggota grup
        if (!$group->users->contains(Auth::id())) {
            abort(403, 'Kamu bukan anggota grup ini.');
        }

        $messages    = $group->messages()->with('sender')->orderBy('created_at', 'asc')->get();
        $channelName = 'chat.group.' . $group->id;

        $users  = User::where('id', '!=', Auth::id())->get();
        $groups = Auth::user()->groups;

        return view('chat.index', compact('messages', 'group', 'users', 'groups', 'channelName'));
    }

    /**
     * Kirim pesan baru (private atau group)
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message'     => 'required|string|max:1000',
            'receiver_id' => 'nullable|exists:users,id',
            'group_id'    => 'nullable|exists:groups,id',
        ]);

        $message = Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'group_id'    => $request->group_id,
            'message'     => $request->message,
        ]);

        // Tembakkan event — Reverb akan langsung menyebarkannya ke browser penerima
        broadcast(new MessageSent($message));

        return response()->json(['status' => 'ok', 'message' => $message->load('sender')]);
    }
}
