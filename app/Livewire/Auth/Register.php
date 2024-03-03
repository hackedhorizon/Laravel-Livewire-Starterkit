<?php

namespace App\Livewire\Auth;

use App\Modules\Auth\Services\RecaptchaService;
use App\Modules\Auth\Services\RegistrationService;
use App\Modules\RateLimiter\Services\RateLimiterService;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Register extends Component
{
    #[Validate('required|string|max:50')]
    public string $name = '';

    #[Validate('required|string|max:30|unique:users,username')]
    public string $username = '';

    #[Validate('required|email|max:50|unique:users,email')]
    public string $email = '';

    #[Validate('required|string|min:6|max:300')]
    public string $password = '';

    private RateLimiterService $rateLimiterService;

    private RecaptchaService $recaptchaService;

    public ?string $recaptchaToken = null;

    private string $pageTitle = '';

    /**
     * Render the Livewire component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.auth.register')->title($this->pageTitle);
    }

    /**
     * Initialize the component.
     *
     * @return void
     */
    public function mount()
    {
        $this->pageTitle = __('Register');
    }

    /**
     * Boot the component with necessary services.
     *
     * @param  RateLimiterService  $rateLimiterService
     * @param  RecaptchaService  $recaptchaService
     * @return void
     */
    public function boot(RateLimiterService $rateLimiterService, RecaptchaService $recaptchaService)
    {
        $this->setUpRateLimiterService($rateLimiterService);

        $this->setUpRecaptchaService($recaptchaService);
    }

    /**
     * Set up the Rate Limiter service with configurations.
     *
     * @param  RateLimiterService  $rateLimiterService
     * @return void
     */
    private function setUpRateLimiterService(RateLimiterService $rateLimiterService)
    {
        $this->rateLimiterService = $rateLimiterService;

        $this->rateLimiterService->setDecayOfSeconds(60);

        $this->rateLimiterService->setCallerMethod('register');

        $this->rateLimiterService->setAllowedNumberOfAttempts(10);

        $this->rateLimiterService->setErrorMessageAttribute('register');
    }

    /**
     * Set up the Recaptcha service with configurations.
     *
     * @param  RecaptchaService  $recaptchaService
     * @return void
     */
    private function setUpRecaptchaService(RecaptchaService $recaptchaService)
    {
        $this->recaptchaService = $recaptchaService;

        $this->recaptchaService->setScoreThreshold(0.5);

        $this->recaptchaService->setErrorMessageAttribute('recaptcha');
    }

    /**
     * Handle property updates.
     *
     * @param  string  $property
     * @return void
     */
    public function updated($property)
    {
        // Check for too many failed attempts if the property is email or username
        if ($property === 'email' || $property === 'username') {
            $this->rateLimiterService->checkTooManyFailedAttempts();
        }

        // Set Recaptcha token value in the service if the property is recaptchaToken
        if ($property === 'recaptchaToken') {
            $this->recaptchaService->setRecaptchaToken($this->recaptchaToken);
        }
    }

    /**
     * Process user registration.
     *
     * @param  RegistrationService  $registrationService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(RegistrationService $registrationService)
    {
        // Check for too many failed registration attempts
        $this->rateLimiterService->checkTooManyFailedAttempts();

        // Validate form fields
        $this->validate();

        // Validate Recaptcha token
        $this->recaptchaService->validateRecaptchaToken();

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
