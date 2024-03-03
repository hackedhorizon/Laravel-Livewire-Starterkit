<?php

namespace App\Livewire\Auth;

use App\Modules\Auth\Services\RecaptchaService;
use App\Modules\Auth\Services\RegistrationService;
use App\Modules\RateLimiter\Services\RateLimiterService;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Register extends Component
{
    // Validation rules for form fields
    #[Validate('required|string|max:50')]
    public string $name = '';

    #[Validate('required|string|max:30|unique:users,username')]
    public string $username = '';

    #[Validate('required|email|max:50|unique:users,email')]
    public string $email = '';

    #[Validate('required|string|min:6|max:300')]
    public string $password = '';

    // RateLimiterService instance for handling registration rate limiting
    private RateLimiterService $rateLimiterService;

    // RecaptchaService instance for handling Google Recaptcha
    private RecaptchaService $recaptchaService;

    // Recaptcha token received from the frontend
    public ?string $recaptchaToken = null;

    // Page title for rendering
    private string $pageTitle = '';

    // Lifecycle method to render the Livewire component
    public function render()
    {
        return view('livewire.auth.register')->title($this->pageTitle);
    }

    // Lifecycle method to initialize the component
    public function mount()
    {
        $this->pageTitle = __('Register');
    }

    // Lifecycle method to configure necessary services during component boot
    public function boot(RateLimiterService $rateLimiterService, RecaptchaService $recaptchaService)
    {
        $this->configureRateLimiterService($rateLimiterService);
        $this->configureRecaptchaService($recaptchaService);
    }

    // Lifecycle method triggered on property updates
    public function updated($property)
    {
        // Check for too many failed attempts if the property is email or username
        if (in_array($property, ['email', 'username'])) {
            $this->rateLimiterService->checkTooManyFailedAttempts();
        }

        // Set Recaptcha token value if the updated property is recaptchaToken and the service is enabled
        $this->updateRecaptchaToken($property);
    }

    // Method to update Recaptcha token if the service is enabled
    private function updateRecaptchaToken($property)
    {
        if (config('services.should_have_recaptcha') && $property === 'recaptchaToken') {
            $this->recaptchaService->setRecaptchaToken($this->recaptchaToken);
        }
    }

    // Method to configure RateLimiterService with specific settings
    private function configureRateLimiterService(RateLimiterService $rateLimiterService)
    {
        $this->rateLimiterService = $rateLimiterService;
        $this->rateLimiterService
            ->setDecayOfSeconds(60)
            ->setCallerMethod('register')
            ->setAllowedNumberOfAttempts(10)
            ->setErrorMessageAttribute('register');
    }

    // Method to configure RecaptchaService with specific settings
    private function configureRecaptchaService(RecaptchaService $recaptchaService)
    {
        $this->recaptchaService = $recaptchaService;
        $this->recaptchaService
            ->setScoreThreshold(0.5)
            ->setErrorMessageAttribute('recaptcha');
    }

    // Method to handle user registration
    public function register(RegistrationService $registrationService)
    {
        // Check for too many failed registration attempts
        $this->rateLimiterService->checkTooManyFailedAttempts();

        // Validate form fields
        $this->validate();

        // Validate Recaptcha token if the service is enabled
        if (config('services.should_have_recaptcha')) {
            $this->recaptchaService->validateRecaptchaToken();
        }

        // Registration attempt
        if ($registrationService->registerUser($this->name, $this->username, $this->email, $this->password)) {
            // Clear the rate limiter
            $this->rateLimiterService->clearLimiter();

            // Redirect user to the main page
            return $this->redirect(route('home'), navigate: true);
        }

        // Registration failed
        $this->addError('register', __('register.failed'));
    }
}
