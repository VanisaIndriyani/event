<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if user already exists
        $userExists = User::where('email', 'user@epicevents.com')->exists();
        
        if (!$userExists) {
            User::create([
                'name' => 'John Doe',
                'email' => 'user@epicevents.com',
                'phone_number' => '081234567891',
                'password' => Hash::make('user123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]);
            
            $this->command->info('User created successfully!');
            $this->command->info('Email: user@epicevents.com');
            $this->command->info('Password: user123');
        } else {
            $this->command->info('User already exists.');
        }
    }
}