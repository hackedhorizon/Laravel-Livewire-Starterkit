<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 dummy users using the User factory
        User::factory(10)->create();

        // Create a default guest user with fixed credentials (username: dummy, password: password)
        $user = User::factory(User::class)->create([
            'name' => fake()->unique()->name(),
            'username' => 'dummy',
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ]);
    }
}
