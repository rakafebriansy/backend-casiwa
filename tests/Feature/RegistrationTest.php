<?php

namespace Tests\Feature;

use Database\Seeders\UniversitySeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RegistrationTest extends TestCase
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
        // DB::delete('DELETE FROM users');
    }
    public function testRegisterSuccess(): void
    {
        $this->seed([UniversitySeeder::class]);
        
        $response = $this->post('/api/register', [
            'first_name'=>'Tria',
            'last_name'=>'Putri Ananda',
            'university_id' => 1,
            'study_program_id' => 1,
            'starting_year' => '2022',
            'email' => 'tapput@gmail.com',
            'password' => '12345678',
            'confirm_password' => '12345678'
        ]);
        $response->assertStatus(201);
    }
    public function testRegisterOptionality(): void
    {
        $this->seed([UniversitySeeder::class]);
        
        $response = $this->post('/api/register', [
            'first_name'=>'Tria',
            'university_id' => 1,
            'study_program_id' => 1,
            'starting_year' => '2022',
            'email' => 'tapput@gmail.com',
            'password' => '12345678',
            'confirm_password' => '12345678'
        ]);
        $response->assertStatus(201);
    }
    public function testRegisterValidationError(): void
    {
        $this->seed([UniversitySeeder::class]);
        
        $response = $this->post('/api/register', [
            'first_name'=>'Tria',
            'university_id' => 1,
            'study_program_id' => 1,
            'starting_year' => '2022',
            'email' => 'tapput@gmail.com',
            'password' => '12345678',
            'confirm_password' => '123456789'
        ]);
        $response->assertStatus(400);
    }
    public function testRegisterUserAlreadyExists(): void
    {
        $this->seed([UserSeeder::class]);
        
        $response = $this->post('/api/register', [
            'first_name'=>'Tria',
            'university_id' => 1,
            'study_program_id' => 1,
            'starting_year' => '2022',
            'email' => 'tapput@gmail.com',
            'password' => '12345678',
            'confirm_password' => '12345678'
        ]);
        $response->assertStatus(400);
        self::assertEquals([
            "errors" => [
                "email" => [
                    "Email sudah terdaftar"
                ]
            ]
        ],$response->json());
    }
}
