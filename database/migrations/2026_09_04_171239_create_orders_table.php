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
            $table->string('order_number')->unique();
            $table->string('customer_first_name');
            $table->string('customer_last_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->string('city')->default('Bamako');
            $table->string('neighborhood'); // Quartier de Bamako (ACI 2000, Hamdallaye, Badalabougou, etc.)
            $table->string('address');
            $table->text('delivery_notes')->nullable();
            $table->string('payment_method'); // 'orange_money', 'cash_on_delivery'
            $table->string('orange_money_number')->nullable();
            $table->string('payment_status')->default('pending'); // 'pending', 'paid', 'failed'
            $table->unsignedInteger('subtotal');
            $table->unsignedInteger('delivery_fee')->default(1500);
            $table->unsignedInteger('discount')->default(0);
            $table->unsignedInteger('total');
            $table->string('status')->default('pending'); // 'pending', 'confirmed', 'in_delivery', 'delivered', 'cancelled'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
