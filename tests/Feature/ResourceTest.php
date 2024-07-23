<?php

namespace Tests\Feature;

use Database\Seeders\UniversitySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ResourceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete('DELETE FROM users');
        DB::delete('DELETE FROM study_programs');
        DB::delete('DELETE FROM study_fields');
        DB::delete('DELETE FROM universities');
        $this->seed([UniversitySeeder::class]);
    }
    public function testUniversities(): void
    {
        
        $response = $this->get("/api/universities")->assertStatus(200);
        Log::channel('stderr')->info($response->json());
    }
    public function testStudyPrograms(): void
    {
        $response = $this->get("/api/study-programs")->assertStatus(200);
        Log::channel('stderr')->info($response->json());
    }
}
