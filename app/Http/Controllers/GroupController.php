<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    /**
     * Buat grup chat baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'member_ids' => 'required|array|min:1',
            'member_ids.*' => 'exists:users,id',
        ]);

        // Buat grup baru di database
        $group = Group::create([
            'name'       => $request->name,
            'created_by' => Auth::id(),
        ]);

        // Tambahkan pembuat grup sebagai anggota pertama
        $memberIds = array_unique(array_merge($request->member_ids, [Auth::id()]));
        $group->users()->attach($memberIds);

        return redirect()->route('chat.group', $group->id)
            ->with('success', 'Grup "' . $group->name . '" berhasil dibuat!');
    }

    /**
     * Tambah anggota baru ke grup yang sudah ada
     */
    public function addMember(Request $request, Group $group)
    {
        // Hanya pembuat grup yang bisa tambah anggota
        if ($group->created_by !== Auth::id()) {
            abort(403, 'Hanya pembuat grup yang bisa menambah anggota.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // syncWithoutDetaching = tambah anggota tanpa hapus yang sudah ada
        $group->users()->syncWithoutDetaching([$request->user_id]);

        return back()->with('success', 'Anggota berhasil ditambahkan!');
    }
}
