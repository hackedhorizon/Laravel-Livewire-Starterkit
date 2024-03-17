<?php

namespace App\Modules\RateLimiter\Interfaces;

/**
 * Interface RateLimiterServiceInterface
 *
 * Represents an interface for rate limiting.
 */
interface RateLimiterServiceInterface
{
    /**
     * Set the duration of the rate limiting.
     */
    public function setDecayOfSeconds(int $decayOfSeconds): self;

    /**
     * Set the caller method (on which function should be the rate-limiting applied).
     */
    public function setCallerMethod(string $callerMethod): self;

    /**
     * Set the attribute name for the error message.
     */
    public function setErrorMessageAttribute(string $errorMessageAttribute): self;

    /**
     * Set the allowed number of attempts within the decay of seconds for rate limiting.
     */
    public function setAllowedNumberOfAttempts(int $allowedNumberOfAttempts): self;

    /**
     * Check if there are too many failed attempts and throw an exception if the limit is reached.
     *
     * @throws \DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException
     */
    public function checkTooManyFailedAttempts(): self;

    /**
     * Clear the rate limiter for the method, resetting any tracked attempts.
     */
    public function clearLimiter(): void;
}
