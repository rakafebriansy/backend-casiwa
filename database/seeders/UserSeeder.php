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
        $this->call([UniversitySeeder::class, BankSeeder::class, AdminSeeder::class]);
        User::insert([
            'first_name' => 'Tria',
            'last_name' => 'Putri Ananda',
            'email' => 'tapput@gmail.com',
            'password' => Hash::make('12345678'),
            'starting_year' => 2022,
            'university_id' => 1,
            'study_program_id' => 1
        ]);
        User::insert([
            'first_name' => 'Raka',
            'last_name' => 'Febrian Syahputra',
            'email' => 'kerenr445@gmail.com',
            'password' => Hash::make('12345678'),
            'starting_year' => 2022,
            'university_id' => 2,
            'study_program_id' => 2
        ]);
    }
}