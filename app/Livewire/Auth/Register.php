<?php

namespace App\Livewire\Auth;

use App\Modules\User\Services\RegistrationService;
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

    public function render()
    {
        return view('livewire.auth.register');
    }

    /**
     * Livewire's 'unique' validation queries the database for email/username existence.
     * To prevent potential abuse, a rate limiter (10 requests/minute) is implemented.
     * Note: The limiter may be bypassed if an actor registers successfully.
     * Google's reCAPTCHA adds an extra layer of security against automated
     * registration attempts, enhancing protection from data fetching attempts.
     */
    public function updated(RegistrationService $registrationService)
    {
        // Check if rate limited
        $registrationService->checkIfRateLimited();
    }

    public function store(RegistrationService $registrationService)
    {
        // Validate data
        $this->validate();

        // Register user, which includes login and adds a flash message to the session
        if ($registrationService->registerUser($this->name, $this->username, $this->email, $this->password)) {

            // Return user to the main page
            return $this->redirect(route('home'), navigate: true);
        }

        // Registration failed
        $this->addError('registration', __('auth.can_not_create_user'));
    }
}
