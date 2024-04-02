<?php

namespace App\Livewire\Auth;

use App\Modules\Authentication\Services\ResetPasswordService;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ResetPassword extends Component
{
    #[Locked]
    public $token;

    public $email;

    public $password;

    public $password_confirmation;

    public $status;

    public function render()
    {
        return view('livewire.auth.reset-password');
    }

    public function mount($token)
    {
        $this->token = $token;
        $this->email = request()->query('email');
    }

    public function resetPassword(ResetPasswordService $resetPasswordService)
    {
        $this->validate([
            'token' => 'required',
            'email' => 'required|email|max:50',
            'password' => 'required|string|min:6|max:300',
        ]);

        $resetPasswordService->setCredentials([
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
            'token' => $this->token,
        ]);

        $status = $resetPasswordService->resetPassword();

        $resetPasswordService->addFlashMessage($status);

        return $this->redirect(route('login'), navigate: true);
    }
}
