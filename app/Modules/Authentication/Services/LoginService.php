<?php

namespace App\Modules\Authentication\Services;

use App\Modules\Authentication\Interfaces\LoginServiceInterface;
use App\Modules\UserManagement\Services\ReadUserService;
use Illuminate\Support\Facades\Auth;

class LoginService implements LoginServiceInterface
{
    private ReadUserService $readUserService;

    public function __construct(ReadUserService $readUserService)
    {
        $this->readUserService = $readUserService;
    }

    /**
     * Attempt to authenticate a user.
     *
     * @param  string  $identifier  The username or email of the user.
     * @param  string  $password  The password provided by the user.
     * @param  bool  $remember  Whether to "remember" the user's authentication.
     * @return bool True if authentication was successful, false otherwise.
     */
    public function attemptLogin(string $identifier, string $password, bool $remember): bool
    {
        $credentials = [];

        $user = $this->readUserService->findUserByUsernameOrEmail($identifier);

        if ($user) {
            $credentials = [
                'email' => $user->email,
                'password' => $password,
            ];

            if (Auth::attempt($credentials, $remember)) {
                $this->onSuccessfulLogin();

                return true;
            }
        }

        $this->onFailedLogin();

        return false;
    }

    /**
     * Handle actions after successful user login.
     *
     * This method regenerates the session and flashes a success message.
     */
    public function onSuccessfulLogin(): void
    {
        session()->regenerate();
        session()->flash('message_success', __('auth.success'));
    }

    /**
     * Handle actions after failed user login.
     *
     * This method can be used for additional handling after a failed login attempt.
     */
    public function onFailedLogin(): void
    {
        // Additional actions after a failed login attempt can be added here.
    }
}
