<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::updateOrCreate(
            ['email' => env('SUPER_ADMIN_EMAIL', 'admin@bamakosugu.com')],
            [
                'name' => env('SUPER_ADMIN_NAME', 'Super Admin BKO SU'),
                'role' => 'super_admin',
                'phone' => env('SUPER_ADMIN_PHONE', '+223 70 00 00 00'),
                'password' => Hash::make(env('SUPER_ADMIN_PASSWORD', 'admin1234')),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command?->info("Compte Super Admin configuré avec succès : {$superAdmin->email} (Rôle: {$superAdmin->role})");
    }
}
