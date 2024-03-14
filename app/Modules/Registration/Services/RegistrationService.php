<?php

namespace App\Modules\Registration\Services;

use App\Models\User;
use App\Modules\Registration\Interfaces\RegistrationServiceInterface;
use App\Modules\UserManagement\Services\WriteUserService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

class RegistrationService implements RegistrationServiceInterface
{
    private WriteUserService $userService;

    public function __construct(WriteUserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * {@inheritdoc}
     */
    public function registerUser(string $name, string $username, string $email, string $password): User
    {
        // Hash the password
        $hashedPassword = Hash::make($password);

        // Create a new user with the provided data
        $user = $this->userService->createUser($name, $username, $email, $hashedPassword);

        // Dispatch a successful registration event -> automatically sends out an email verification link to the user
        if (config('services.should_verify_email')) {
            event(new Registered($user));
        }

        // Return the registered user object
        return $user;
    }
}
