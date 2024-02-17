<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FailedLoginAttempt>
 */
class FailedLoginAttemptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Note: if you want to use the factory with fully random data, uncomment these lines & delete the code under it.

        // return [
        //     'user_id' => 1,
        //     'email_address' => fake()->unique()->safeEmail(),
        //     'ip_address' => fake()->ipv4(),
        // ];

        // Get a random user from the User model
        $userId = User::inRandomOrder()->first()->id;
        $user = User::where('id', $userId)->first();

        return [
            'user_id' => $userId,
            'email_address' => $user['email'],
            'ip_address' => fake()->ipv4(),
        ];
    }
}
