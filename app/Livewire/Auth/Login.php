<?php

namespace App\Livewire\Auth;

use App\Modules\User\Services\AuthService;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Login extends Component
{
    #[Validate('required|string|max:50')]
    public $identifier = '';

    #[Validate('required|string|min:6|max:300')]
    public $password = '';

    public function render()
    {
        return view('livewire.auth.login');
    }

    public function login(AuthService $authService)
    {
        // Validate form fields
        $this->validate();

        // Authentication attempt
        if ($authService->attemptLogin($this->identifier, $this->password)) {
            return $this->redirect(route('home'), navigate: true);
        }

        // Authentication failed
        $this->addError('authentication', 'Invalid identifier or password.');
    }
}
