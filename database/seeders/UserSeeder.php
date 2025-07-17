<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->updateOrCreate([
            'email' => config('auth.root.email'),
        ], [
            'name' => config('auth.root.name'),
            'email_verified_at' => now()->toDateTimeString(),
            'password' => Hash::make(config('auth.root.password')),
            'role' => User::ROLE_ADMIN,
        ]);
    }
}
