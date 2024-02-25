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
     * @var int The allowed number of attempts for rate limiting.
     */
    private int $allowedNumberOfAttempts;

    /**
     * @var string The attribute name for the error message.
     */
    private string $errorMessageAttribute;

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
            $this->rateLimit($this->allowedNumberOfAttempts);
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
        $this->clearRateLimiter();
    }
}
