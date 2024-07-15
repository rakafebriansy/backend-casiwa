<?php

namespace Database\Seeders;

use App\Models\University;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UniversitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(StudyProgramSeeder::class);
        University::insert([
            'id' => 1,
            'name' => 'Universitas Jember',
        ]);
        University::insert([
            'id' => 2,
            'name' => 'Universitas Airlangga',
        ]);
    }
}
