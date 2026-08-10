<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_default_users_when_env_is_empty(): void
    {
        $this->seed(UserSeeder::class);

        $this->assertDatabaseHas('users', ['email' => 'staf@kua.local', 'role' => User::ROLE_STAFF]);
        $this->assertDatabaseHas('users', ['email' => 'operator@kua.local', 'role' => User::ROLE_OPERATOR]);
        $this->assertDatabaseHas('users', ['email' => 'kepala@kua.local', 'role' => User::ROLE_KEPALA]);
    }

    public function test_seeder_users_can_login_with_default_password(): void
    {
        $this->seed(UserSeeder::class);

        $this->assertTrue(auth()->validate(['email' => 'staf@kua.local', 'password' => 'password']));
        $this->assertTrue(auth()->validate(['email' => 'operator@kua.local', 'password' => 'password']));
        $this->assertTrue(auth()->validate(['email' => 'kepala@kua.local', 'password' => 'password']));
    }
}