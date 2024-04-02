<?php

namespace App\Modules\Authentication\DTOs;

/**
 * Data Transfer Object (DTO) for representing a login attempt.
 */
class LoginAttemptDTO
{
    /**
     * The user ID associated with the login attempt.
     */
    private string $user_id;

    /**
     * The email address used for the login attempt.
     */
    private string $email_address;

    /**
     * The IP address from which the login attempt was made.
     */
    private string $ip_address;

    /**
     * Create a new LoginAttemptDTO instance.
     *
     * @param  string  $user_id  The user ID associated with the login attempt.
     * @param  string  $email_address  The email address used for the login attempt.
     * @param  string  $ip_address  The IP address from which the attempt was made.
     */
    public function __construct(
        string $user_id,
        string $email_address,
        string $ip_address
    ) {
        $this->user_id = $user_id;
        $this->email_address = $email_address;
        $this->ip_address = $ip_address;
    }

    /**
     * Get the user ID associated with the login attempt.
     */
    public function getUserId(): string
    {
        return $this->user_id;
    }

    /**
     * Get the email address used for the login attempt.
     */
    public function getEmailAddress(): string
    {
        return $this->email_address;
    }

    /**
     * Get the IP address from which the login attempt was made.
     */
    public function getIpAddress(): string
    {
        return $this->ip_address;
    }
}
