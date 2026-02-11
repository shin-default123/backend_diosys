<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@diocese.com'], 
            [
                'name' => 'Admin User',
                'password' => bcrypt('password123'),
                'role' => 'Admin',
            ]
        );
        User::firstOrCreate(
            ['email' => 'ochavo.sheenmilger@gmail.com'], 
            [
                'name' => 'Admin',
                'password' => bcrypt('password123'),
                'role' => 'Admin',
            ]
        );
        $this->call(SystemSeeder::class);
    }
}