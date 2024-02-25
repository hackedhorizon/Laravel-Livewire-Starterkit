<?php

namespace App\Livewire\Auth;

use App\Modules\Auth\Services\LoginService;
use App\Modules\RateLimiter\Services\RateLimiterService;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Login extends Component
{
    #[Validate('required|string|max:50')]
    public $identifier = '';

    #[Validate('required|string|min:6|max:300')]
    public $password = '';

    private RateLimiterService $rateLimiterService;

    public function render()
    {
        return view('livewire.auth.login');
    }

    public function boot(RateLimiterService $rateLimiterService)
    {
        // Set up the rate limiter service to allow 3 login attempts per minute

        $this->rateLimiterService = $rateLimiterService;

        $this->rateLimiterService->setAllowedNumberOfAttempts(3);

        $this->rateLimiterService->setErrorMessageAttribute('login');
    }

    public function login(LoginService $loginService)
    {
        // Add rate limit for login attempts
        $this->rateLimiterService->checkTooManyFailedAttempts();

        // Validate form fields
        $this->validate();

        // Authentication attempt
        if ($loginService->attemptLogin($this->identifier, $this->password)) {

            // Authentication successful
            $this->rateLimiterService->clearLimiter();

            // Redirect the user to the home route after successful login
            return $this->redirect(route('home'), navigate: true);
        }

        // Authentication failed
        $this->addError('authentication', __('auth.failed'));
    }
}
