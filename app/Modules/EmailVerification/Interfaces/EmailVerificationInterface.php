<?php

namespace App\Modules\EmailVerification\Interfaces;

use App\Models\User;

/**
 * Interface EmailVerificationInterface
 */
interface EmailVerificationInterface
{
    /**
     * Sends a verification email to the user.
     */
    public function sendVerificationEmail(): void;

    /**
     * Verifies the user's email using the provided ID and hash.
     *
     * @param  int  $id  The user ID.
     * @param  string  $hash  The verification hash.
     * @return bool True if the email is successfully verified, false otherwise.
     */
    public function verifyEmail(int $id, string $hash): bool;

    /**
     * Sets the user for the email verification process.
     *
     * @param  User  $user  The user instance.
     */
    public function setUser(User $user): void;
}
