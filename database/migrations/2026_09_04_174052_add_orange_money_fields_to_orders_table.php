<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('orange_money_order_id')->nullable()->after('orange_money_number');
            $table->string('orange_money_pay_token')->nullable()->after('orange_money_order_id');
            $table->string('orange_money_notif_token')->nullable()->after('orange_money_pay_token');
            $table->string('orange_money_transaction_id')->nullable()->after('orange_money_notif_token');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'orange_money_order_id',
                'orange_money_pay_token',
                'orange_money_notif_token',
                'orange_money_transaction_id',
            ]);
        });
    }
};
