<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('vendor_name')->default('BKO Supermarché');
            $table->string('reference')->unique();
            $table->unsignedInteger('price'); // FCFA
            $table->unsignedInteger('original_price')->nullable(); // FCFA barré
            $table->unsignedSmallInteger('discount_percent')->nullable();
            $table->string('badge')->nullable(); // ex: "-30%", "Offre Flash", "Nouveau"
            $table->unsignedInteger('stock')->default(10);
            $table->decimal('rating', 3, 1)->default(4.8);
            $table->unsignedInteger('reviews_count')->default(12);
            $table->string('image_url');
            $table->json('gallery')->nullable();
            $table->text('description');
            $table->json('features')->nullable();
            $table->boolean('is_flash_deal')->default(false);
            $table->timestamp('flash_deal_ends_at')->nullable();
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('is_recommended')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
