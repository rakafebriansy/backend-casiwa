<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bank::insert([
            'id' => 1,
            'name' => 'BRI'
        ]);
        Bank::insert([
            'id' => 2,
            'name' => 'BNI'
        ]);
        Bank::insert([
            'id' => 3,
            'name' => 'BCA'
        ]);
    }
}
