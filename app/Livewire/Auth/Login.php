<?php

namespace App\Livewire\Auth;

use App\Modules\User\Services\ReadUserService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Login extends Component
{
    #[Validate()]
    public $identifier;

    #[Validate()]
    public $password;

    public $status;

    public function rules()
    {
        return [
            'identifier' => 'required|string|max:50',
            'password' => 'required|string|min:6|max:300',
        ];
    }

    public function login(ReadUserService $userService)
    {
        // Validate input fields
        $validated = $this->validate();

        // Find user in database
        $user = $userService->findUserByUsernameOrEmail($validated['identifier']);

        // If not found show a status message
        if ($user === null) {
            $this->status = "We didn't find any user with the provided data.";
        }

        // If found, log the user in and add a session flash message
        else {
            Auth::login($user);

            session()->flash('message', 'Successful login!');

            return $this->redirect(route('home'), navigate: true);
        }
    }

    public function mount()
    {
        $this->identifier = '';

        $this->password = '';

        $this->status = '';
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
