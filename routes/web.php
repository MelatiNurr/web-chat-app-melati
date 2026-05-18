<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('chat.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute-rute untuk fitur Chat
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/user/{user}', [ChatController::class, 'privateChat'])->name('chat.private');
    Route::get('/chat/group/{group}', [ChatController::class, 'groupChat'])->name('chat.group');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');

    // Rute-rute untuk fitur Grup
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::post('/groups/{group}/members', [GroupController::class, 'addMember'])->name('groups.addMember');
});

// Daftarkan route otentikasi WebSocket (wajib ada agar private channel bisa bekerja)
use Illuminate\Support\Facades\Broadcast;
Broadcast::routes(['middleware' => ['web', 'auth']]);

require __DIR__.'/auth.php';
