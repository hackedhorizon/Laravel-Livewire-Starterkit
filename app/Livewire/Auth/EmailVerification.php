<?php

namespace App\Livewire\Auth;

use App\Modules\Registration\Services\EmailVerificationService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EmailVerification extends Component
{
    public function render()
    {
        return view('livewire.auth.email-verification');
    }

    public function mount()
    {
        $this->checkVerificationStatus();
    }

    private function checkVerificationStatus()
    {
        // Check if the user is logged in
        if (! Auth::check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        // Check if the email is already verified
        if (Auth::user()->hasVerifiedEmail()) {
            return $this->redirect(route('home'), navigate: true);
        }
    }

    // Resend the verification email to the user
    public function resendEmailVerification(EmailVerificationService $emailVerificationService)
    {
        // Set the service
        $emailVerificationService->setUser(Auth::user());

        // Resend the verification email
        $emailVerificationService->sendVerificationEmail();

        // Add flash message to notify the user
        session()->flash('message_success', __('register.verification_email_sent'));

        // Refresh the page
        return $this->redirect(route('verification.notice'), navigate: true);
    }

    // Verify the email after user clicked on the email
    public function verifyEmail(EmailVerificationService $emailVerificationService, $id, $hash)
    {
        // Set the service
        $emailVerificationService->setUser(Auth::user());

        // Failure: Redirect to 404 page
        if (! $emailVerificationService->verifyEmail($id, $hash)) {
            return abort(404);
        }

        // Success: Mark the email as verified and notify the user
        event(new Verified(Auth::user()));

        // Notify the user
        session()->flash('message_success', __('register.email_verified'));

        // Redirect to the home page
        return redirect(route('home'));
    }
}
