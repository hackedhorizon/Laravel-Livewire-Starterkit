<?php

namespace App\Modules\Registration\Services;

use App\Models\User;
use App\Modules\Registration\Interfaces\EmailVerificationInterface;

class EmailVerificationService implements EmailVerificationInterface
{
    private User $user;

    /**
     * {@inheritdoc}
     */
    public function sendVerificationEmail(): void
    {
        $this->user->sendEmailVerificationNotification();
    }

    /**
     * {@inheritdoc}
     */
    public function verifyEmail(int $id, string $hash): bool
    {
        // Check if the hash provided in the URL matches the hashed version of the user's email
        if (! hash_equals((string) $hash, sha1($this->user->getEmailForVerification()))) {
            return false; // If the hashes don't match, the email verification failed
        }

        // If the user updated his email, we are going to replace the old with the new one.
        if ($this->user->temporary_email !== null) {
            $this->user->email = $this->user->temporary_email;
            $this->user->temporary_email = null;
        }

        // Mark the user's email as verified
        $this->user->markEmailAsVerified();

        // Return true (verification succeeded)
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}
