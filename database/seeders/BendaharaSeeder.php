<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class BendaharaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Anra (Bendahara)', // Ganti namamu
            'email' => 'komanganrasansya@gmail.com', // Ganti emailmu
            'password' => Hash::make('password123'), // GANTI DENGAN PASSWORD AMAN!
            'role' => 'bendahara', // Ini kuncinya
        ]);
    }
}