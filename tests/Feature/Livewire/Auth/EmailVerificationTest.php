<?php

namespace Tests\Feature\Livewire\Auth;

use App\Livewire\Auth\EmailVerification;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Render the email verification component successfully.
     *
     * Steps:
     *  1. Render the email verification component
     *  2. Check that the response status is 200
     */
    public function renders_successfully()
    {
        if (! config('services.should_verify_email')) {
            $this->markTestSkipped('Email verification disabled.');
        }

        Livewire::test(EmailVerification::class)
            ->assertStatus(200);
    }

    /**
     * Test: User can resend email verification.
     *
     * Steps:
     *  1. Create a user with a non-verified email.
     *  2. Act as the user, test the EmailVerification component, and call the 'resendEmailVerification' method.
     *  3. Assert that the user is redirected to the verification notice route and a success message is flashed.
     */
    public function test_user_can_resend_email_verification()
    {
        if (! config('services.should_verify_email')) {
            $this->markTestSkipped('Email verification disabled.');
        }

        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        Livewire::actingAs($user)
            ->test(EmailVerification::class)
            ->call('resendEmailVerification')
            ->assertRedirect(route('verification.notice'))
            ->assertSessionHas('message_success', __('register.verification_email_sent'));
    }

    /**
     * Test: Application redirects to login if the user is not logged in on mount.
     *
     * Steps:
     *  1. Test the EmailVerification component without acting as any user.
     *  2. Assert that the user is redirected to the login route.
     */
    public function test_application_redirects_to_login_if_user_not_logged_in()
    {
        if (! config('services.should_verify_email')) {
            $this->markTestSkipped('Email verification disabled.');
        }

        Livewire::test(EmailVerification::class)
            ->assertRedirect(route('login'));
    }

    /**
     * Test: Redirects to home if the email is already verified on mount.
     *
     * Steps:
     *  1. Create a user with a verified email.
     *  2. Act as the user, test the EmailVerification component, and assert redirection to the home route.
     */
    public function test_application_redirects_to_home_if_email_already_verified()
    {
        if (! config('services.should_verify_email')) {
            $this->markTestSkipped('Email verification disabled.');
        }

        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(EmailVerification::class)
            ->assertRedirect(route('home'));
    }

    /**
     * Test: User can verify email.
     *
     * Steps:
     *  1. Create a user with a non-verified email.
     *  2. Act as the user, test the EmailVerification component, and call the 'verifyEmail' method with a valid hash.
     *  3. Assert redirection to the home route and a success message flashed.
     *  4. Assert that the user's email is marked as verified.
     *  5. Assert that the Verified event is dispatched with the correct user.
     */
    public function test_user_can_verify_email()
    {
        if (! config('services.should_verify_email')) {
            $this->markTestSkipped('Email verification disabled.');
        }

        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        // Mock the event class to assert that it's fired
        Event::fake([Verified::class]);

        $hash = sha1($user->getEmailForVerification());

        Livewire::actingAs($user)
            ->test(EmailVerification::class)
            ->call('verifyEmail', $user->id, $hash)
            ->assertRedirect(route('home'))
            ->assertSessionHas('message_success', __('register.email_verified'));

        $this->assertTrue($user->fresh()->hasVerifiedEmail());

        // Assert that the Verified event was fired
        Event::assertDispatched(Verified::class, function ($event) use ($user) {
            return $event->user->id === $user->id;
        });
    }

    /**
     * Test: User cannot verify email with an invalid hash.
     *
     * Steps:
     *  1. Create a user with a non-verified email.
     *  2. Act as the user, test the EmailVerification component, and call the 'verifyEmail' method with an invalid hash.
     *  3. Assert that the response status is 404.
     *  4. Assert that the user's email is still not marked as verified.
     */
    public function test_user_can_not_verify_email_with_invalid_hash()
    {
        if (! config('services.should_verify_email')) {
            $this->markTestSkipped('Email verification disabled.');
        }

        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        Livewire::actingAs($user)
            ->test(EmailVerification::class)
            ->call('verifyEmail', $user->id, 'invalid_hash')
            ->assertStatus(404);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }
}
