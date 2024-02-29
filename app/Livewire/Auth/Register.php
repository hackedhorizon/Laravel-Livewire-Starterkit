<?php

namespace App\Livewire\Auth;

use App\Modules\Auth\Services\RegistrationService;
use App\Modules\RateLimiter\Services\RateLimiterService;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Register extends Component
{
    #[Validate('required|string|max:50')]
    public string $name;

    #[Validate('required|string|max:30|unique:users,username')]
    public string $username;

    #[Validate('required|email|max:50|unique:users,email')]
    public string $email;

    #[Validate('required|string|min:6|max:300')]
    public string $password;

    private RateLimiterService $rateLimiterService;

    private string $pageTitle = '';

    public function render()
    {
        return view('livewire.auth.register')->title($this->pageTitle);
    }

    public function mount()
    {
        $this->pageTitle = __('Register');
    }

    public function boot(RateLimiterService $rateLimiterService)
    {
        // Set up the rate limiter service to allow 10 registration attempts per minute

        $this->rateLimiterService = $rateLimiterService;

        $this->rateLimiterService->setAllowedNumberOfAttempts(10);

        $this->rateLimiterService->setErrorMessageAttribute('registration');
    }

    public function updated($property)
    {
        // If the property either email or username, we check if the user tries to brute-forcing these values
        if ($property === 'email' || $property === 'username') {
            $this->rateLimiterService->checkTooManyFailedAttempts();
        }
    }

    public function store(RegistrationService $registrationService)
    {
        $this->rateLimiterService->checkTooManyFailedAttempts();

        // Validate data
        $this->validate();

        // Register user, which includes login and adds a flash message to the session
        if ($registrationService->registerUser($this->name, $this->username, $this->email, $this->password)) {

            // Clear the rate limiter
            $this->rateLimiterService->clearLimiter();

            // Return user to the main page
            return $this->redirect(route('home'), navigate: true);
        }

        // Registration failed
        $this->addError('register', __('register.failed'));
    }
}
