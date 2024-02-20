<?php

namespace App\Modules\User\Services;

use App\Models\User;
use App\Modules\User\Interfaces\RegistrationServiceInterface;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegistrationService implements RegistrationServiceInterface
{
    use WithRateLimiting;

    public WriteUserService $userService;

    public function __construct(WriteUserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * {@inheritdoc}
     */
    public function registerUser(string $name, string $username, string $email, string $password): ?User
    {
        // Check if rate limited
        $this->checkIfRateLimited();

        // Hash the password
        $hashedPassword = Hash::make($password);

        // Create a new user with the provided data
        $user = $this->userService->createUser($name, $username, $email, $hashedPassword);

        // If user successfully created, log in and set session message
        if ($user) {

            // Login the newly registered user
            Auth::login($user);

            // Set flash message for successful registration
            session()->flash('message', 'Your registration was successful!');

            // Return user
            return $user;
        }

        // User creation failed, return null
        return null;
    }

    public function checkIfRateLimited(): void
    {
        try {
            $this->rateLimit(10);
        } catch (TooManyRequestsException $exception) {
            throw ValidationException::withMessages([
                'registration' => __('auth.throttle', ['seconds' => $exception->secondsUntilAvailable]),
            ]);
        }
    }
}
