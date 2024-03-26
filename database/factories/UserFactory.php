<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $shouldHaveLocalization = config('services.should_have_localization');

        if ($shouldHaveLocalization) {
            $languageCodes = array_keys(config('app.locales'));    // Fetch language codes from config
            $languageCode = $languageCodes[array_rand($languageCodes)]; // Choose random language code
        } else {
            // Fallback to default language code
            $languageCode = config('app.fallback_locale');
        }

        return [
            'name' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'temporary_email' => null,
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'language' => $languageCode,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
