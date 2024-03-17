<?php

namespace App\Livewire\Auth;

use App\Modules\Authentication\Services\LoginService;
use App\Modules\RateLimiter\Services\RateLimiterService;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Login extends Component
{
    #[Validate('required|string|max:50')]
    public string $identifier = '';

    #[Validate('required|string|min:6|max:300')]
    public string $password = '';

    public bool $remember = false;

    private RateLimiterService $rateLimiterService;

    private string $pageTitle = '';

    public function render()
    {
        return view('livewire.auth.login')->title($this->pageTitle);
    }

    public function mount()
    {
        $this->pageTitle = __('Login');
    }

    public function boot(RateLimiterService $rateLimiterService)
    {
        $this->rateLimiterService = $rateLimiterService;

        $this->rateLimiterService->setDecayOfSeconds(60);

        $this->rateLimiterService->setCallerMethod('login');

        $this->rateLimiterService->setAllowedNumberOfAttempts(3);

        $this->rateLimiterService->setErrorMessageAttribute('login');
    }

    public function login(LoginService $loginService)
    {
        // Check for too many failed login attempts
        $this->rateLimiterService->checkTooManyFailedAttempts(60, 'login');

        // Validate form fields
        $this->validate();

        // Authentication attempt
        if ($loginService->attemptLogin($this->identifier, $this->password, $this->remember)) {

            // Clear the rate limiter
            $this->rateLimiterService->clearLimiter();

            // Redirect user to the main page
            return $this->redirect(route('home'), navigate: true);
        }

        // Authentication failed
        $this->addError('login', __('auth.failed'));
    }
}
