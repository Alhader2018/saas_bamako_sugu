<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('name'); // Libellé affiché au client
            $table->string('file_path'); // Chemin de stockage privé (ex: digital_products/xyz.pdf)
            $table->string('file_name'); // Nom de fichier original
            $table->unsignedBigInteger('file_size')->default(0); // Taille en octets
            $table->string('mime_type')->nullable();
            $table->string('version')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_files');
    }
};
