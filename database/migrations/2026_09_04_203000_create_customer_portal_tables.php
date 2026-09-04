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
        // 1. Table des Favoris (Wishlist)
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'product_id']);
        });

        // 2. Table des Adresses de livraison clients
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('label')->default('Domicile'); // Ex: Domicile, Bureau, Famille
            $table->string('recipient_name');
            $table->string('phone');
            $table->string('city')->default('Bamako');
            $table->string('commune')->nullable(); // Ex: Commune IV
            $table->string('neighborhood'); // Ex: Hamdallaye ACI, ACI 2000
            $table->text('address'); // Rue, porte, repère connu
            $table->text('delivery_notes')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // 3. Table des Notifications clients
        Schema::create('customer_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('message');
            $table->string('type')->default('info'); // order_confirmed, in_preparation, in_delivery, delivered, cancelled, info
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_notifications');
        Schema::dropIfExists('customer_addresses');
        Schema::dropIfExists('favorites');
    }
};
