<?php

namespace App\Modules\User\Services;

use App\Models\User;
use App\Modules\User\Interfaces\RegistrationServiceInterface;
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

        // Login the newly registered user
        Auth::login($user);

        // Set flash message for successful registration
        session()->flash('message', 'Your registration was successful!');

        return $user;
    }
}
