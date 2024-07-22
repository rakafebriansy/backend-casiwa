<?php

namespace Database\Seeders;

use App\Models\StudyField;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudyFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StudyField::insert([
            'id' => 1,
            'name' => 'Ilmu Komputer'
        ]);
        StudyField::insert([
            'id' => 2,
            'name' => 'Psikologi'
        ]);
        StudyField::insert([
            'id' => 3,
            'name' => 'Ilmu Keperawatan'
        ]);
    }
}
