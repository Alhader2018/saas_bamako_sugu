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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id')->nullable()->unique()->after('email');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('google_id');
            }
            if (!Schema::hasColumn('users', 'city')) {
                $table->string('city')->default('Bamako')->after('phone');
            }
            if (!Schema::hasColumn('users', 'neighborhood')) {
                $table->string('neighborhood')->nullable()->after('city');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('neighborhood');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['google_id', 'avatar', 'city', 'neighborhood', 'address']);
        });
    }
};
