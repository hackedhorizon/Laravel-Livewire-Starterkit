<?php

namespace App\Modules\Auth\Services;

use App\Models\User;
use App\Modules\Auth\Interfaces\RegistrationServiceInterface;
use App\Modules\User\Services\WriteUserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegistrationService implements RegistrationServiceInterface
{
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
        // Hash the password
        $hashedPassword = Hash::make($password);

        // Create a new user with the provided data
        $user = $this->userService->createUser($name, $username, $email, $hashedPassword);

        // If user successfully created, log in and set session message
        if ($user) {

            // Login the newly registered user
            Auth::login($user);

            // Set flash message for successful registration
            session()->flash('message_success', __('register.success'));

            // Return user
            return $user;
        }

        // User creation failed, return null
        return null;
    }
}
