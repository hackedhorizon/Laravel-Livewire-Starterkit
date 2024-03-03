<?php

namespace App\Modules\RateLimiter\Interfaces;

interface RateLimiterServiceInterface
{
    /**
     * Set the duration of the rate limiting.
     */
    public function setDecayOfSeconds(int $decayOfSeconds): void;

    /**
     * Set the caller method (on which function should be the rate-limiting applied).
     */
    public function setCallerMethod(string $callerMethod): void;

    /**
     * Set the attribute name for the error message.
     */
    public function setErrorMessageAttribute(string $errorMessageAttribute): void;

    /**
     * Set the allowed number of attempts within the decay of seconds for rate limiting.
     */
    public function setAllowedNumberOfAttempts(int $allowedNumberOfAttempts): void;

    /**
     * Check if there are too many failed attempts and throw an exception if the limit is reached.
     *
     * @throws \DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException
     */
    public function checkTooManyFailedAttempts(): void;

    /**
     * Clear the rate limiter for the method, resetting any tracked attempts.
     */
    public function clearLimiter(): void;
}
