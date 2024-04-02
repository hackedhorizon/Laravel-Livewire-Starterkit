<?php

namespace App\Modules\Authentication\Services;

use App\Modules\Authentication\Interfaces\ResetPasswordServiceInterface;
use App\Modules\Session\Services\MessageService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ResetPasswordService implements ResetPasswordServiceInterface
{
    private string $email;

    private string $password;

    private string $password_confirmation;

    private string $token;

    private MessageService $messageService;

    public function __construct(
        MessageService $messageService,
    ) {
        $this->messageService = $messageService;
    }

    public function setCredentials(array $credentials): void
    {
        $this->email = $credentials['email'];
        $this->password = $credentials['password'];
        $this->password_confirmation = $credentials['password_confirmation'];
        $this->token = $credentials['token'];
    }

    public function sendResetPasswordLink(string $email): string
    {
        return Password::sendResetLink(['email' => $email]);
    }

    public function resetPassword(): string
    {
        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status;
    }

    public function addFlashMessage($status): void
    {
        switch ($status) {
            case Password::RESET_LINK_SENT:
                $this->messageService->addSuccessMessage(__('passwords.sent'));
                break;
            case Password::PASSWORD_RESET:
                $this->messageService->addSuccessMessage(__('passwords.reset'));
                break;
            case Password::INVALID_USER:
                $this->messageService->addErrorMessage(__('passwords.user'));
                break;
            case Password::INVALID_TOKEN:
                $this->messageService->addErrorMessage(__('passwords.token'));
                break;
            case Password::RESET_THROTTLED:
                $this->messageService->addErrorMessage(__('passwords.throttled'));
                break;
            default:
                $this->messageService->addErrorMessage(__('passwords.error'));
                break;
        }
    }
}
