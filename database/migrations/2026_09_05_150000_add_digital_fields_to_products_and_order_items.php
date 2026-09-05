<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('product_type')->default('physical')->after('category_id'); // 'physical' or 'digital'
            $table->string('digital_type')->nullable()->after('product_type'); // 'ebook', 'pdf', 'video', 'course', 'audio', 'software', 'zip', 'other'
            $table->string('access_type')->default('file_download')->after('digital_type'); // 'file_download', 'external_link', 'video_player'
            $table->text('external_access_url')->nullable()->after('access_type');
            $table->unsignedInteger('download_limit')->nullable()->after('external_access_url'); // null = illimité
            $table->unsignedInteger('download_expiry_days')->nullable()->after('download_limit'); // null = pas d'expiration
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->string('product_type')->default('physical')->after('product_name');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'product_type',
                'digital_type',
                'access_type',
                'external_access_url',
                'download_limit',
                'download_expiry_days',
            ]);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('product_type');
        });
    }
};
