<?php

namespace App\Livewire\Auth;

use App\Modules\User\Services\WriteUserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

    public function render()
    {
        return view('livewire.auth.register');
    }

    public function mount()
    {
        $this->name = '';
        $this->username = '';
        $this->email = '';
        $this->password = '';
    }

    public function store(WriteUserService $userService)
    {
        // Validate data
        $this->validate();

        // Hash the password
        $this->password = Hash::make($this->password);

        // Create a new user via service with the validated data
        $user = $userService->createUser($this->name, $this->username, $this->email, $this->password);

        // Login user
        Auth::login($user);

        // Set flash message
        session()->flash('message', 'Your registered successfully!');

        // Return user to the main page
        return $this->redirect(route('home'), navigate: true);
    }
}
