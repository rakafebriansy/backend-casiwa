<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            'first_name' => 'Tria',
            'last_name' => 'Putri Ananda',
            'email' => 'tapput@gmail.com',
            'password' => '12345678',
            'starting_year' => 2022,
            'university_id' => 1,
            'study_program_id' => 1
        ]);
    }
}