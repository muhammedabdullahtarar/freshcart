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

        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@freshcart.com',
            'password' => Hash::make('superadmin123'),
            'type' => 'super_admin',
            'email_verified_at' => now(),
        ]);

    }
}
