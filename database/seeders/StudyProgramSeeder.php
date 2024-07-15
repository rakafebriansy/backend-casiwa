<?php

namespace Database\Seeders;

use App\Models\StudyProgram;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudyProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(FacultySeeder::class);

        StudyProgram::insert([
            'id' => 1,
            'name' => 'Sistem Informasi',
            'faculty_id' => 1
        ]);
        StudyProgram::insert([
            'id' => 2,
            'name' => 'Teknologi Informasi',
            'faculty_id' => 1
        ]);
        StudyProgram::insert([
            'id' => 3,
            'name' => 'Informatika',
            'faculty_id' => 1
        ]);
    }
}
