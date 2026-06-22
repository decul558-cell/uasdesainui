<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Satu percakapan per user (1 user = 1 thread chat dengan toko)
        Schema::create('chat_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['bot', 'open', 'closed'])->default('bot');
            // bot   = masih dilayani chatbot
            // open  = sudah eskalasi ke admin, belum selesai
            // closed= selesai ditangani
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
        });

        // Pesan di dalam satu percakapan
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_conversation_id')->constrained()->cascadeOnDelete();
            $table->enum('sender_type', ['user', 'bot', 'admin'])->default('user');
            $table->foreignId('sender_id')->nullable(); // user_id pengirim (null jika bot)
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_conversations');
    }
};