<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stock_transaction', function (Blueprint $table) {
            // Drop old columns
            $table->dropForeign(['inventory_id']);
            $table->dropColumn('inventory_id');
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            // New columns
            $table->string('transaction_id')->unique()->after('id');
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete()->after('transaction_id');
            $table->foreignId('size_id')->constrained('sizes')->cascadeOnDelete()->after('product_id');
            $table->string('reference_id')->nullable()->after('quantity');
            $table->integer('old_stock')->default(0)->after('reference_id');
            $table->integer('new_stock')->default(0)->after('old_stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_transaction', function (Blueprint $table) {
            $table->dropColumn(['transaction_id', 'reference_id', 'old_stock', 'new_stock']);
            $table->dropForeign(['product_id']);
            $table->dropForeign(['size_id']);
            $table->dropColumn(['product_id', 'size_id']);

            $table->foreignId('inventory_id')->constrained('inventory')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        });
    }
};
