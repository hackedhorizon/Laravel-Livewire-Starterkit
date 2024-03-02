<?php

namespace App\Modules\RateLimiter\Interfaces;

interface RateLimiterServiceInterface
{
    /**
     * Check if there are too many failed attempts and throw an exception if the limit is reached.
     *
     * @throws \DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException
     */
    public function checkTooManyFailedAttempts(): void;

    /**
     * Set the allowed number of attempts within a minute for rate limiting.
     */
    public function setAllowedNumberOfAttempts(int $allowedNumberOfAttempts): void;

    /**
     * Set the attribute name for the error message.
     */
    public function setErrorMessageAttribute(string $errorMessageAttribute): void;

    /**
     * Clear the rate limiter, resetting any tracked attempts.
     */
    public function clearLimiter(): void;
}
