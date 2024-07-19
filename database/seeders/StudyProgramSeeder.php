<?php

namespace Database\Seeders;

use App\Models\StudyField;
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
        $this->call(StudyField::class);

        StudyProgram::insert([
            'id' => 1,
            'name' => 'Sistem Informasi',
            'study_field_id' => 1
        ]);
        StudyProgram::insert([
            'id' => 2,
            'name' => 'Teknologi Informasi',
            'study_field_id' => 1
        ]);
        StudyProgram::insert([
            'id' => 3,
            'name' => 'Informatika',
            'study_field_id' => 1
        ]);
    }
}
