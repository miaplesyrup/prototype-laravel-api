<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing records to start with a clean slate
        User::truncate();

        // Insert sample user data with hashed passwords
        User::create([
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'status' => 'active',
            'confirmation_token' => null, // Add the confirmation_token field
        ]);

        User::create([
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
            'status' => 'active',
            'confirmation_token' => null, // Add the confirmation_token field
        ]);
    }
}
