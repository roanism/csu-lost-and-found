<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Check if admin exists
        if (!Admin::where('username', 'admin')->exists()) {
            Admin::create([
                'name' => 'Administrator',
                'username' => 'admin',
                'password' => Hash::make('admin123'), // Change this password in production
            ]);
        }
    }
}