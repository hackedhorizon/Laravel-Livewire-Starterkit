<?php

namespace App\Livewire\User;

use App\Modules\RateLimiter\Services\RateLimiterService;
use App\Modules\Registration\Services\EmailVerificationService;
use App\Modules\UserManagement\Services\ReadUserService;
use App\Modules\UserManagement\Services\WriteUserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Profile extends Component
{
    #[Validate('required|string|max:50')]
    public string $name = '';

    #[Validate('string|max:30')]
    public string $username = '';

    #[Validate('email|max:50')]
    public string $email = '';

    #[Validate('string|min:6|max:300')]
    public string $password = '';

    private string $pageTitle = '';

    private bool $isEmailVerificationEnabled = false;

    private $user;

    private ReadUserService $readUserService;

    private WriteUserService $writeUserService;

    private EmailVerificationService $emailVerificationService;

    private RateLimiterService $rateLimiterService;

    public function render()
    {
        return view('livewire.user.profile')->title($this->pageTitle);
    }

    public function mount(ReadUserService $readUserService)
    {
        $this->pageTitle = __('Account');
        $this->readUserService = $readUserService;
        $this->initializeUserData();
    }

    public function boot(WriteUserService $writeUserService, EmailVerificationService $emailVerificationService, RateLimiterService $rateLimiterService)
    {
        $this->isEmailVerificationEnabled = config('services.should_verify_email');
        $this->emailVerificationService = $emailVerificationService;
        $this->rateLimiterService = $rateLimiterService;
        $this->writeUserService = $writeUserService;
        $this->user = Auth::user();

        $this->rateLimiterService
            ->setDecayOfSeconds(60)
            ->setCallerMethod('updateProfileInformation')
            ->setAllowedNumberOfAttempts(10)
            ->setErrorMessageAttribute('update');
    }

    public function updatedUsername($value)
    {
        $this->rateLimiterService->checkTooManyFailedAttempts();
        $this->validateUsername();
    }

    public function updatedEmail($value)
    {
        $this->rateLimiterService->checkTooManyFailedAttempts();
        $this->validateEmail();
    }

    public function updateProfileInformation()
    {
        $this->rateLimiterService->checkTooManyFailedAttempts();
        $this->validateProfileInformation();

        $userData = [
            'name' => $this->name,
            'username' => $this->username,
        ];

        if ($this->password) {
            $userData['password'] = Hash::make($this->password);
        }

        if ($this->isEmailVerificationEnabled && $this->email !== $this->user->email) {
            $userData['temporary_email'] = $this->email;
            $this->sendEmailVerification();
            session()->flash('message_success', __('profile.profile_updated_with_verification'));
        } else {
            $userData['email'] = $this->email;
            session()->flash('message_success', __('profile.profile_updated_without_verification'));
        }

        $this->writeUserService->updateUser($this->user->id, $userData);

        return $this->redirect(route('home'), navigate: true);
    }

    public function deleteUser()
    {
        $this->writeUserService->deleteUser($this->user->id);
        session()->flash('message_success', __('profile.delete_successfull'));

        return $this->redirect(route('home'), navigate: true);
    }

    private function validateUsername()
    {
        $this->validateOnly('username', ['username' => 'required|alpha_dash|string|max:30|unique:users,username,'.optional($this->user)->id]);
    }

    private function validateEmail()
    {
        $this->validateOnly('email', ['email' => 'required|email|max:50|unique:users,email,'.optional($this->user)->id]);
    }

    private function validateProfileInformation()
    {
        $this->validateUsername();
        $this->validateEmail();
        $this->validate();
    }

    private function initializeUserData()
    {
        $this->name = $this->readUserService->getUserProperty('name', $this->user->id);
        $this->username = $this->readUserService->getUserProperty('username', $this->user->id);
        $this->email = $this->readUserService->getUserProperty('email', $this->user->id);
    }

    private function sendEmailVerification()
    {
        $this->emailVerificationService->setUser($this->user);
        $this->emailVerificationService->sendVerificationEmail();
    }
}
