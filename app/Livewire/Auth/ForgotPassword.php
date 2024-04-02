<?php

namespace App\Livewire\Auth;

use App\Modules\Authentication\Services\ResetPasswordService;
use App\Modules\RateLimiter\Services\RateLimiterService;
use Livewire\Component;

class ForgotPassword extends Component
{
    public $email;

    public $status;

    private RateLimiterService $rateLimiterService;

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }

    public function boot(RateLimiterService $rateLimiterService)
    {
        $this->rateLimiterService = $rateLimiterService;
        $this->rateLimiterService
            ->setDecayOfSeconds(60)
            ->setCallerMethod('sendResetPasswordEmailNotification')
            ->setAllowedNumberOfAttempts(5)
            ->setErrorMessageAttribute('reset-password');
    }

    public function sendResetPasswordEmailNotification(ResetPasswordService $resetPasswordService)
    {
        $this->rateLimiterService->checkTooManyFailedAttempts();

        $this->validate([
            'email' => 'required|email|max:50',
        ]);

        $status = $resetPasswordService->sendResetPasswordLink($this->email);

        $resetPasswordService->addFlashMessage($status);

        return $this->redirect(route('home'), navigate: true);
    }
}
