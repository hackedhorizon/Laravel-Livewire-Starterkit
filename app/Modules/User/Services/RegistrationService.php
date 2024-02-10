<?php

namespace App\Modules\User\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegistrationService
{
    // Instance of WriteUserService for creating new users
    public WriteUserService $userService;

    // Constructor to inject the WriteUserService dependency
    public function __construct(WriteUserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Register a new user.
     *
     * @param  string  $name  The name of the user.
     * @param  string  $username  The username of the user.
     * @param  string  $email  The email of the user.
     * @param  string  $password  The plain text password of the user.
     * @return mixed The created user entity.
     */
    public function registerUser(string $name, string $username, string $email, string $password)
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
