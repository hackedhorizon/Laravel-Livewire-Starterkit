<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Create a default guest user with fixed credentials (username: test, password: password)
        $user = User::factory(User::class)->create([
            'username' => 'test',
            'password' => bcrypt('password'),
        ]);

        // Create 10 dummy users using the User factory
        User::factory(10)->create();

    }
}
