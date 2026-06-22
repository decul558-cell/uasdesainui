<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bundles', function (Blueprint $table) {
            $table->string('name')->default('')->after('id');
            $table->string('slug')->unique()->default('')->after('name');
            $table->text('description')->nullable()->after('slug');
            $table->string('image')->nullable()->after('description');
            $table->decimal('original_price', 10, 2)->default(0)->after('image');
            $table->decimal('bundle_price', 10, 2)->default(0)->after('original_price');
            $table->integer('stock')->default(0)->after('bundle_price');
            $table->boolean('is_active')->default(true)->after('stock');
        });
    }
    public function down(): void
    {
        Schema::table('bundles', function (Blueprint $table) {
            $table->dropColumn([
                'name', 'slug', 'description', 'image',
                'original_price', 'bundle_price', 'stock', 'is_active'
            ]);
        });
    }
};