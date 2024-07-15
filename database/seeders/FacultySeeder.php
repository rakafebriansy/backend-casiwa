<?php

namespace Database\Seeders;

use App\Models\Faculty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Faculty::insert([
            'id' => 1,
            'name' => 'Fakultas Ilmu Komputer'
        ]);
        Faculty::insert([
            'id' => 2,
            'name' => 'Fakultas Ilmu Budaya'
        ]);
    }
}
