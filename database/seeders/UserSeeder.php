<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::create([
            'name' => 'Allicia',
            'email' => 'allicia@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'super_adm',
        ]);

        User::create([
            'name' => 'Avicenna',
            'email' => 'apicenna@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Ladiva',
            'email' => 'Ladiva@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'staff',
        ]);
    }
}
