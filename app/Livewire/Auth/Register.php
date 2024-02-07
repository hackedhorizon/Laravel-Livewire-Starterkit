<?php

namespace App\Livewire\Auth;

use App\Modules\User\Services\WriteUserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Register extends Component
{
    #[Validate]
    public $name;

    #[Validate]
    public $username;

    #[Validate]
    public $email;

    #[Validate]
    public $password;

    // Real-time validation via LiveWire
    public function rules()
    {
        return [
            'name' => 'required|string|max:50',
            'username' => 'required|string|max:30|unique:users,username',
            'email' => 'required|email|max:50|unique:users,email',
            'password' => 'required|string|min:6|max:300',
        ];
    }

    public function store(WriteUserService $userService)
    {
        // Validate
        $validated = $this->validate();

        // Hash the password
        $validated['password'] = Hash::make($this->password);

        // Create a new user via service with the validated data
        $user = $userService->createUser($validated['name'], $validated['username'], $validated['email'], $validated['password']);

        // Login user
        Auth::login($user);

        // Set flash message
        session()->flash('message', 'Your registered successfully!');

        // Return user to the main page
        return $this->redirect(route('home'), navigate: true);
    }

    public function mount()
    {
        $this->name = '';
        $this->username = '';
        $this->email = '';
        $this->password = '';
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
