<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('carts', function (Blueprint $table) {
            $table->foreignId('bundle_id')->nullable()->constrained()->nullOnDelete()->after('user_id');
            $table->decimal('price', 10, 2)->nullable()->after('quantity');
            $table->nullableMorphs('cartable'); // opsional, skip kalau mau simple
        });

        // product_id juga perlu nullable sekarang (karena cart bisa bundle)
        Schema::table('carts', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable()->change();
        });
    }

    public function down(): void {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['bundle_id']);
            $table->dropColumn(['bundle_id', 'price', 'cartable_id', 'cartable_type']);
        });
    }
};