<?php

namespace App\Modules\RateLimiter\Services;

use App\Modules\RateLimiter\Interfaces\RateLimiterServiceInterface;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Illuminate\Validation\ValidationException;

class RateLimiterService implements RateLimiterServiceInterface
{
    use WithRateLimiting;

    /**
     * @var int The decaying of rate limiter.
     */
    private int $decayOfSeconds;

    /**
     * @var int The allowed number of attempts within the decay of seconds.
     */
    private int $allowedNumberOfAttempts;

    /**
     * @var string The method which the rate limiter is called from.
     */
    private string $callerMethod;

    /**
     * @var string The attribute name for the error message.
     */
    private string $errorMessageAttribute;

    /**
     * {@inheritdoc}
     */
    public function setDecayOfSeconds(int $decayOfSeconds): void
    {
        $this->decayOfSeconds = $decayOfSeconds;
    }

    /**
     * {@inheritdoc}
     */
    public function setAllowedNumberOfAttempts(int $allowedNumberOfAttempts): void
    {
        $this->allowedNumberOfAttempts = $allowedNumberOfAttempts;
    }

    /**
     * {@inheritdoc}
     */
    public function setCallerMethod(string $callerMethod): void
    {
        $this->callerMethod = $callerMethod;
    }

    /**
     * {@inheritdoc}
     */
    public function setErrorMessageAttribute(string $errorMessageAttribute): void
    {
        $this->errorMessageAttribute = $errorMessageAttribute;
    }

    /**
     * {@inheritdoc}
     */
    public function checkTooManyFailedAttempts(): void
    {
        try {
            $this->rateLimit($this->allowedNumberOfAttempts, $this->decayOfSeconds, $this->callerMethod);
        } catch (TooManyRequestsException $exception) {
            throw ValidationException::withMessages([
                $this->errorMessageAttribute => __('auth.throttle', ['seconds' => $exception->secondsUntilAvailable]),
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function clearLimiter(): void
    {
        $this->clearRateLimiter($this->callerMethod);
    }
}
