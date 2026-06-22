<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Kapan terakhir kali user update keranjang
            $table->timestamp('last_activity_at')->nullable()->after('updated_at');
            // Ditandai abandon oleh sistem (setelah X jam tidak checkout)
            $table->timestamp('abandoned_at')->nullable()->after('last_activity_at');
            // Apakah sudah diingatkan (untuk keperluan masa depan kirim reminder)
            $table->boolean('is_reminded')->default(false)->after('abandoned_at');
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn(['last_activity_at', 'abandoned_at', 'is_reminded']);
        });
    }
};