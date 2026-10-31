<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@skillmatch.id'],
            [
                'name' => 'Admin SkillMatch',
                'username' => 'admin',
                'password' => Hash::make('Admin123!'),
                'role' => 'admin',
                'status_akun' => 'aktif',
                'email_verified_at' => now(),
            ]
        );

        Profile::firstOrCreate(
            ['user_id' => $admin->id],
            [
                'prodi' => 'Teknik Informatika',
                'is_open_to_join' => false,
                'is_portfolio_public' => true,
                'is_sosmed_public' => true,
            ]
        );
    }
}
