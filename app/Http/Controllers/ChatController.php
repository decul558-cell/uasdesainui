<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Services\ChatBotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Halaman chat untuk user (1 user = 1 percakapan).
     */
    public function index()
    {
        $conversation = ChatConversation::firstOrCreate(
            ['user_id' => Auth::id()],
            ['status' => 'bot']
        );

        $messages = $conversation->messages()->with('sender')->get();

        // Tandai pesan dari admin sebagai sudah dibaca saat user membuka chat
        $conversation->messages()
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('pages.chat', compact('conversation', 'messages'));
    }

    /**
     * Kirim pesan baru dari user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $conversation = ChatConversation::firstOrCreate(
            ['user_id' => Auth::id()],
            ['status' => 'bot']
        );

        // Simpan pesan user
        ChatMessage::create([
            'chat_conversation_id' => $conversation->id,
            'sender_type'          => 'user',
            'sender_id'            => Auth::id(),
            'message'              => $request->message,
        ]);

        $conversation->update(['last_message_at' => now()]);

        // Kalau percakapan masih dalam mode bot, coba balas otomatis
        if ($conversation->status === 'bot') {
            $botReply = ChatBotService::reply($request->message);

            if ($botReply) {
                ChatMessage::create([
                    'chat_conversation_id' => $conversation->id,
                    'sender_type'          => 'bot',
                    'sender_id'            => null,
                    'message'              => $botReply,
                ]);
            } else {
                // Tidak ketemu jawaban -> eskalasi ke admin
                ChatMessage::create([
                    'chat_conversation_id' => $conversation->id,
                    'sender_type'          => 'bot',
                    'sender_id'            => null,
                    'message'              => ChatBotService::fallbackMessage(),
                ]);
                $conversation->update(['status' => 'open']);
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Polling: ambil pesan baru sejak ID tertentu (untuk auto-refresh).
     */
    public function poll(Request $request)
    {
        $conversation = ChatConversation::where('user_id', Auth::id())->first();

        if (!$conversation) {
            return response()->json(['messages' => [], 'status' => 'bot']);
        }

        $afterId = $request->query('after_id', 0);

        $messages = $conversation->messages()
            ->where('id', '>', $afterId)
            ->with('sender')
            ->get();

        // Tandai pesan admin yang baru masuk sebagai dibaca (karena user sedang lihat chat)
        $conversation->messages()
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'messages' => $messages,
            'status'   => $conversation->status,
        ]);
    }
}