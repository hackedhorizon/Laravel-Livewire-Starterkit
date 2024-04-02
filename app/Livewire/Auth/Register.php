<?php

namespace App\Livewire\Auth;

use App\Modules\Google\Services\RecaptchaService;
use App\Modules\RateLimiter\Services\RateLimiterService;
use App\Modules\Registration\Services\RegistrationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Register extends Component
{
    #[Validate('required|string|max:50')]
    public string $name = '';

    #[Validate('required|alpha_dash|string|max:30|unique:users,username')]
    public string $username = '';

    #[Validate('required|email|max:50|unique:users,email')]
    public string $email = '';

    #[Validate('required|string|min:6|max:300')]
    public string $password = '';

    private RateLimiterService $rateLimiterService;

    private RecaptchaService $recaptchaService;

    private bool $emailVerificationServiceEnabled = false;

    private bool $recaptchaServiceEnabled = false;

    public ?string $recaptchaToken = null;

    private string $pageTitle = '';

    public function render()
    {
        return view('livewire.auth.register')->title($this->pageTitle);
    }

    public function mount()
    {
        $this->pageTitle = __('Register');
    }

    public function boot(
        RateLimiterService $rateLimiterService,
        RecaptchaService $recaptchaService,
    ) {
        $this->configureRateLimiterService($rateLimiterService);
        $this->configureRecaptchaService($recaptchaService);

        // Get the configuration settings
        $this->recaptchaServiceEnabled = config('services.should_have_recaptcha');
        $this->emailVerificationServiceEnabled = config('services.should_verify_email');
    }

    public function updated($property)
    {
        $this->checkFailedAttemptsAndRecaptcha($property);
    }

    private function checkFailedAttemptsAndRecaptcha($property)
    {
        // Check for too many failed attempts if the property is email or username
        if (in_array($property, ['email', 'username'])) {
            $this->rateLimiterService->checkTooManyFailedAttempts();
        }

        // Set Recaptcha token value if the updated property is recaptchaToken and the service is enabled
        $this->updateRecaptchaToken($property);
    }

    private function updateRecaptchaToken($property)
    {
        // If the recaptcha token updates on the frontend, update on the backend (recaptcha service) as well
        if ($this->recaptchaServiceEnabled && $property === 'recaptchaToken') {
            $this->recaptchaService->setRecaptchaToken($this->recaptchaToken);
        }
    }

    private function configureRateLimiterService(RateLimiterService $rateLimiterService)
    {
        // Configure the rate limiter service to allow 10 requests / minute on calling the register method
        $this->rateLimiterService = $rateLimiterService;
        $this->rateLimiterService
            ->setDecayOfSeconds(60)
            ->setCallerMethod('register')
            ->setAllowedNumberOfAttempts(10)
            ->setErrorMessageAttribute('register');
    }

    private function configureRecaptchaService(RecaptchaService $recaptchaService)
    {
        // Configure the recaptcha service and set the score threshold to 0.5
        $this->recaptchaService = $recaptchaService;
        $this->recaptchaService
            ->setScoreThreshold(0.5)
            ->setErrorMessageAttribute('recaptcha');
    }

    public function register(RegistrationService $registrationService)
    {
        // Clear the rate limiter & validation
        $this->rateLimiterService->checkTooManyFailedAttempts();
        $this->validate();
        $this->validateRecaptcha();

        // Create user
        $user = $registrationService->registerUser($this->name, $this->username, $this->email, $this->password);

        // Handle creation
        $this->handleSuccessfulRegistration($user);

    }

    private function validateRecaptcha()
    {
        // Validate recaptcha token
        if ($this->recaptchaServiceEnabled) {
            $this->recaptchaService->validateRecaptchaToken();
        }
    }

    private function handleSuccessfulRegistration($user)
    {
        // Add success message to the flash
        session()->flash('message_success', __('register.success'));

        // Clear rate limiter
        $this->rateLimiterService->clearLimiter();

        // Log in the user
        Auth::login($user);

        // If the email verification enabled, notify the user from the sent verification email
        if ($this->emailVerificationServiceEnabled) {
            session()->flash('message_success', __('register.verification_email_sent'));

            return $this->redirect(route('verification.notice'), navigate: true);
        }

        // Redirect user to the home page
        return $this->redirect(route('home'), navigate: true);
    }
}
