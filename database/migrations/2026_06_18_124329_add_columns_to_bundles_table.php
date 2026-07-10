<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bundles', function (Blueprint $table) {
            if (!Schema::hasColumn('bundles', 'name')) {
                $table->string('name')->default('')->after('id');
            }
            if (!Schema::hasColumn('bundles', 'slug')) {
                $table->string('slug')->unique()->default('')->after('name');
            }
            if (!Schema::hasColumn('bundles', 'description')) {
                $table->text('description')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('bundles', 'image')) {
                $table->string('image')->nullable()->after('description');
            }
            if (!Schema::hasColumn('bundles', 'original_price')) {
                $table->decimal('original_price', 10, 2)->default(0)->after('image');
            }
            if (!Schema::hasColumn('bundles', 'bundle_price')) {
                $table->decimal('bundle_price', 10, 2)->default(0)->after('original_price');
            }
            if (!Schema::hasColumn('bundles', 'stock')) {
                $table->integer('stock')->default(0)->after('bundle_price');
            }
            if (!Schema::hasColumn('bundles', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('stock');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bundles', function (Blueprint $table) {
            $columns = ['name', 'slug', 'description', 'image', 'original_price', 'bundle_price', 'stock', 'is_active'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('bundles', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
