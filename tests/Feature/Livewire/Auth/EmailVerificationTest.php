<?php

namespace Tests\Feature\Livewire\Auth;

use App\Livewire\Auth\EmailVerification;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Define the routes necessary for testing (in a case this functionality is disabled)
        Route::get('/verify-email', [\App\Livewire\Auth\EmailVerification::class, '__invoke'])->name('verification.notice');
        Route::get('/verify-email/{id}/{hash}', [\App\Livewire\Auth\EmailVerification::class, 'verifyEmail'])->name('verification.verify');
        Route::post('/verify-email/send-notification', [\App\Livewire\Auth\EmailVerification::class, 'sendVerificationEmail'])->name('verification.send');
    }

    /**
     * Test: Render the email verification component successfully.
     *
     * Steps:
     *  1. Render the email verification component
     *  2. Check that the response status is 200
     */
    public function renders_successfully()
    {
        config(['services.should_verify_email' => true]);

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
        config(['services.should_verify_email' => true]);

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
        config(['services.should_verify_email' => true]);

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
        config(['services.should_verify_email' => true]);

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
        config(['services.should_verify_email' => true]);

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
        config(['services.should_verify_email' => true]);

        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        Livewire::actingAs($user)
            ->test(EmailVerification::class)
            ->call('verifyEmail', $user->id, 'invalid_hash')
            ->assertStatus(404);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    /**
     * Test: User's email is updated if they have a temporary email set.
     *
     * Steps:
     *  1. Create a user with a temporary email set.
     *  2. Act as the user, test the EmailVerification component, and call the 'verifyEmail' method with a valid hash.
     *  3. Assert redirection to the home route and a success message flashed.
     *  4. Assert that the user's email is marked as verified.
     *  5. Assert that the user's email is updated to the temporary email.
     *  6. Assert that the temporary email is set to null.
     *  7. Assert that the Verified event is dispatched with the correct user.
     */
    public function test_user_email_updated_if_temporary_email_set()
    {
        config(['services.should_verify_email' => true]);

        $temporaryEmail = 'temporary@example.com';

        $user = User::factory()->create([
            'email_verified_at' => null,
            'temporary_email' => $temporaryEmail,
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
        $this->assertEquals($temporaryEmail, $user->fresh()->email);
        $this->assertNull($user->fresh()->temporary_email);

        // Assert that the Verified event was fired
        Event::assertDispatched(Verified::class, function ($event) use ($user) {
            return $event->user->id === $user->id;
        });
    }
}
