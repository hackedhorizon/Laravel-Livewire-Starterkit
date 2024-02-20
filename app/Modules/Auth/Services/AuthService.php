<?php

namespace App\Modules\Auth\Services;

use App\Modules\Auth\Interfaces\AuthServiceInterface;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService implements AuthServiceInterface
{
    use WithRateLimiting;

    /**
     * {@inheritdoc}
     */
    public function attemptLogin(string $identifier, string $password): bool
    {
        $credentials = [];

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $credentials = [
                'email' => $identifier,
                'password' => $password,
            ];
        } else {
            $credentials = [
                'username' => $identifier,
                'password' => $password,
            ];
        }

        if (Auth::attempt($credentials)) {
            $this->onSuccessfulLogin();

            return true;
        }

        $this->onFailedLogin();

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function onSuccessfulLogin(): void
    {
        $this->clearRateLimiter();
        session()->regenerate();
        session()->flash('message', __('auth.success'));
    }

    /**
     * {@inheritdoc}
     */
    public function onFailedLogin(): void
    {
        try {
            $this->rateLimit(3);
        } catch (TooManyRequestsException $exception) {
            throw ValidationException::withMessages([
                'authentication' => __('auth.throttle', ['seconds' => $exception->secondsUntilAvailable]),
            ]);
        }
    }
}
