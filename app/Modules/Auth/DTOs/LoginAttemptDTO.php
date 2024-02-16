<?php

namespace App\Modules\Auth\DTOs;

class LoginAttemptDTO
{
    public function __construct(
        private string $user_id,
        private string $email_address,
        private string $ip_address
    ) {
    }

    public function getUserId(): string
    {
        return $this->user_id;
    }

    public function getEmailAddress(): string
    {
        return $this->email_address;
    }

    public function getIpAddress(): string
    {
        return $this->ip_address;
    }

    public function setUserId(string $ip_address): void
    {
        $this->ip_address = $ip_address;
    }

    public function setEmailAddress(string $email_address): void
    {
        $this->email_address = $email_address;
    }

    public function setIpAddress(string $ip_address): void
    {
        $this->ip_address = $ip_address;
    }
}
