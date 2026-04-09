<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop foreign keys first (needed to drop unique indexes) - MySQL compatible
        try {
            DB::statement('ALTER TABLE inventory DROP FOREIGN KEY inventory_product_id_foreign');
        } catch (\Exception $e) { }
        try {
            DB::statement('ALTER TABLE inventory DROP FOREIGN KEY inventory_sizes_id_foreign');
        } catch (\Exception $e) { }

        // Drop unique indexes - MySQL compatible
        try {
            DB::statement('ALTER TABLE inventory DROP INDEX inventory_product_id_unique');
        } catch (\Exception $e) { }
        try {
            DB::statement('ALTER TABLE inventory DROP INDEX inventory_sizes_id_unique');
        } catch (\Exception $e) { }
        
        Schema::table('inventory', function (Blueprint $table) {
            // Add foreign keys back (without unique constraint)
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->foreign('sizes_id')->references('id')->on('sizes')->cascadeOnDelete();
            
            // Add composite unique constraint (product_id + sizes_id)
            $table->unique(['product_id', 'sizes_id'], 'inventory_product_size_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory', function (Blueprint $table) {
            $table->dropUnique('inventory_product_size_unique');
            $table->unique('product_id');
            $table->unique('sizes_id');
        });
    }
};
