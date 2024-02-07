<?php

namespace App\Livewire\Auth;

use App\Modules\User\Services\WriteUserService;
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
        $validated = $this->validate();

        $validated['password'] = Hash::make($this->password);

        $user = $userService->createUser($validated['name'], $validated['username'], $validated['email'], $validated['password']);
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
