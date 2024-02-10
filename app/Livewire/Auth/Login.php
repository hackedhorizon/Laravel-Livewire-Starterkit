<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
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

    public function login()
    {
        // Validate form fields.
        $this->validate();

        // Authentication was successful
        if (
            Auth::attempt(['email' => $this->identifier, 'password' => $this->password]) ||

            Auth::attempt(['username' => $this->identifier, 'password' => $this->password])
        ) {
            session()->regenerate();

            session()->flash('message', 'Successful login!');

            return $this->redirect(route('home'), navigate: true);
        }

        // Authentication failed
        else {
            $this->addError('email', 'Invalid identifier or password.');
        }
    }
}
