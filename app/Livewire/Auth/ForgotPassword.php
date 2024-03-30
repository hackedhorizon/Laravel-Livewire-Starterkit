<?php

namespace App\Livewire\Auth;

use App\Modules\Authentication\Services\ResetPasswordService;
use Livewire\Component;

class ForgotPassword extends Component
{
    public $email;

    public $status;

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }

    public function sendResetPasswordEmailNotification(ResetPasswordService $resetPasswordService)
    {
        $this->validate([
            'email' => 'required|email|max:50',
        ]);

        $status = $resetPasswordService->sendResetPasswordLink($this->email);

        $resetPasswordService->addFlashMessage($status);

        return $this->redirect(route('home'), navigate: true);
    }
}
