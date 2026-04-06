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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique(); // PO20240331-123
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            
            // Customer info
            $table->string('customer_name', 100);
            $table->string('customer_contact', 20); // WhatsApp number
            $table->text('customer_address');
            
            // Order details
            $table->json('size_quantities'); // {"S": 5, "M": 10, "L": 3}
            $table->integer('total_quantity');
            $table->text('design_notes')->nullable();
            $table->string('design_file_path', 255)->nullable();
            
            // Order status
            $table->string('status', 20)->default('pending'); // pending, confirmed, production, sewing, packing, shipped, completed, cancelled
            $table->text('admin_notes')->nullable();
            
            // Pricing (optional, for future)
            $table->decimal('unit_price', 12, 2)->nullable();
            $table->decimal('total_price', 12, 2)->nullable();
            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
