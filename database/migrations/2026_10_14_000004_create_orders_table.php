<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique()->index();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('delivery_address');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery_fee', 10, 2)->default(50.00);
            $table->decimal('grand_total', 10, 2);
            $table->string('payment_method')->default('cod');
            $table->string('payment_status')->default('pending');
            $table->string('order_status')->default('pending')->index();
            $table->text('notes')->nullable();
            $table->timestamp('estimated_delivery_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
