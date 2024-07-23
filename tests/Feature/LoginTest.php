<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class LoginTest extends TestCase
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
    }
    public function testRegisterSuccess(): void
    {
        $this->seed([UserSeeder::class]);
        
        $response = $this->post('/api/login', [
            'email' => 'tapput@gmail.com',
            'password' => '12345678',
        ]);
        $response->assertStatus(200);
        Log::channel('stderr')->info($response->json()); 
    }
    public function testRegisterValidationError(): void
    {
        $this->seed([UserSeeder::class]);
        
        $response = $this->post('/api/login', [
            'email' => 'tapput@gmail.com',
            'password' => '',
        ]);
        $response->assertStatus(400);
        Log::channel('stderr')->info($response->json()); 

    }
    public function testRegisterFailed(): void
    {
        $this->seed([UserSeeder::class]);
        
        $response = $this->post('/api/login', [
            'email' => 'tapput@gmail.com',
            'password' => '123456789',
        ]);
        $response->assertStatus(401);
        Log::channel('stderr')->info($response->json()); 

    }
}
