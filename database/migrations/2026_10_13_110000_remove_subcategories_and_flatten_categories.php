<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Safely migrates existing products from subcategories to their appropriate top-level categories,
     * removes the subcategories, and drops the parent_id column.
     */
    public function up(): void
    {
        if (Schema::hasColumn('categories', 'parent_id')) {
            // 1. Migrate existing products assigned to subcategories up to their parent category
            $subcategories = DB::table('categories')->whereNotNull('parent_id')->get();
            foreach ($subcategories as $sub) {
                DB::table('products')
                    ->where('category_id', $sub->id)
                    ->update(['category_id' => $sub->parent_id]);
            }

            // 2. Remove former subcategories from categories table
            DB::table('categories')->whereNotNull('parent_id')->delete();

            // 3. Drop foreign key constraint and parent_id column
            Schema::table('categories', function (Blueprint $table) {
                $table->dropForeign(['parent_id']);
                $table->dropColumn('parent_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('categories', 'parent_id')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->foreignId('parent_id')->nullable()->after('id')->constrained('categories')->nullOnDelete();
            });
        }
    }
};
