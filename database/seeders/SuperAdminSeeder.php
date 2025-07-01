<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'admin',
            'jabatan' => 'Admin',
            'nama' => 'Admin',
            'no_wa' => '081234567890',
            'role' => 'admin',
            'profil' => '',
            'password' => Hash::make('password'), // Pastikan mengganti 'password' dengan kata sandi yang aman
        ]);
    }
}