<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_success_login(): void
    {
        $this->seed([UserSeeder::class]);
        $response = $this->post('/api/auth/login', [
            'email' => config('auth.root.email'),
            'password' => config('auth.root.password'),
        ]);
        $response->assertStatus(200);
    }

    public function test_failed_login(): void
    {
        $this->seed([UserSeeder::class]);
        $response = $this->post('/api/auth/login', [
            'email' => config('auth.root.email'),
            'password' => $this->faker->password(16),
        ]);
        $response->assertStatus(401);
    }

    public function test_success_signup(): void
    {
        $password = $this->faker->password(16);
        $response = $this->post('/api/auth/signup', [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $password,
            'password_confirmation' => $password,
        ]);
        $response->assertStatus(200);
    }

    public function test_password_mismatch_signup(): void
    {
        $response = $this->post('/api/auth/signup', [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $this->faker->password(16),
            'password_confirmation' => $this->faker->password(16),
        ]);
        $response->assertStatus(302);
    }

    public function test_email_already_exists(): void
    {
        $this->seed([UserSeeder::class]);
        $password = $this->faker->password(16);
        $response = $this->post('/api/auth/signup', [
            'name' => $this->faker->name(),
            'email' => config('auth.root.email'),
            'password' => $password,
            'password_confirmation' => $password,
        ]);
        $response->assertStatus(302);
    }

    public function test_without_required_fields(): void
    {
        $password = $this->faker->password(16);
        $response = $this->post('/api/auth/signup', [
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $password,
            'password_confirmation' => $password,
        ]);
        $response->assertStatus(302);
    }
}
