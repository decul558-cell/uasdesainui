<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Daftar semua percakapan, diurutkan: open dulu, lalu yang terbaru.
     */
    public function index()
    {
        $conversations = ChatConversation::with(['user', 'lastMessage'])
            ->orderByRaw("CASE WHEN status = 'open' THEN 0 WHEN status = 'bot' THEN 1 ELSE 2 END")
            ->orderByDesc('last_message_at')
            ->paginate(15);

        return view('admin.chats.index', compact('conversations'));
    }

    /**
     * Detail percakapan + form balas.
     */
    public function show(ChatConversation $conversation)
    {
        $conversation->load(['user', 'messages.sender']);

        // Tandai semua pesan user sebagai dibaca oleh admin
        $conversation->messages()
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('admin.chats.show', compact('conversation'));
    }

    /**
     * Admin membalas pesan -> otomatis ambil alih percakapan (status: open).
     */
    public function reply(Request $request, ChatConversation $conversation)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        ChatMessage::create([
            'chat_conversation_id' => $conversation->id,
            'sender_type'          => 'admin',
            'sender_id'            => Auth::id(),
            'message'              => $request->message,
        ]);

        $conversation->update([
            'status'          => 'open',
            'last_message_at' => now(),
        ]);

        return back()->with('success', 'Balasan terkirim!');
    }

    /**
     * Tandai percakapan selesai.
     */
    public function close(ChatConversation $conversation)
    {
        $conversation->update(['status' => 'closed']);

        return back()->with('success', 'Percakapan ditandai selesai.');
    }

    /**
     * Polling untuk admin: ambil pesan baru di satu percakapan.
     */
    public function poll(Request $request, ChatConversation $conversation)
    {
        $afterId = $request->query('after_id', 0);

        $messages = $conversation->messages()
            ->where('id', '>', $afterId)
            ->with('sender')
            ->get();

        $conversation->messages()
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'messages' => $messages,
            'status'   => $conversation->status,
        ]);
    }
}