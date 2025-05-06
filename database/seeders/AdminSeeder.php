<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'name' => 'CSU Admin',
            'email' => 'admin@csu.edu.ph',
            'password' => Hash::make('password123'), // Change this password after first login!
        ]);
    }
}