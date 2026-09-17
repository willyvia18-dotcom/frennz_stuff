<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@frennz.stuff'],
            [
                'name' => 'Admin Frennz',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        $this->call(VoucherSeeder::class);
    }
}