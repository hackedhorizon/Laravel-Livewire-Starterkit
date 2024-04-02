<?php

namespace Tests\Feature\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Livewire\Livewire;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Reset password link screen can be rendered.
     *
     * Steps:
     *  1. Access the 'forgot-password' route.
     *  2. Assert that the 'auth.forgot-password' Livewire component is rendered.
     *  3. Assert HTTP status code 200.
     */
    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $this->get('/forgot-password')
            ->assertSeeLivewire('auth.forgot-password')
            ->assertStatus(200);
    }

    /**
     * Test: Reset password link can be requested.
     *
     * Steps:
     *  1. Fake the notification.
     *  2. Create a user.
     *  3. Initialize Livewire test for 'auth.forgot-password' component.
     *  4. Set user email.
     *  5. Call 'sendResetPasswordEmailNotification' method.
     *  6. Assert that the reset password notification is sent to the user.
     */
    public function test_reset_password_link_can_be_requested(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        Livewire::test('auth.forgot-password')
            ->set('email', $user->email)
            ->call('sendResetPasswordEmailNotification');

        Notification::assertSentTo($user, ResetPassword::class);
    }

    /**
     * Test: Reset password screen can be rendered.
     *
     * Steps:
     *  1. Fake the notification.
     *  2. Create a user.
     *  3. Initialize Livewire test for 'auth.forgot-password' component.
     *  4. Set user email.
     *  5. Call 'sendResetPasswordEmailNotification' method.
     *  6. Assert that the reset password notification is sent to the user.
     *  7. Assert that the 'auth.reset-password' Livewire component is rendered.
     *  8. Assert HTTP status code 200.
     */
    public function test_reset_password_screen_can_be_rendered(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        Livewire::test('auth.forgot-password')
            ->set('email', $user->email)
            ->call('sendResetPasswordEmailNotification');

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            Livewire::test('auth.reset-password', ['token' => $notification->token, 'email' => $user->email])
                ->assertSeeLivewire('auth.reset-password')
                ->assertStatus(200);

            return true;
        });
    }

    /**
     * Test: Password can be reset with valid token.
     *
     * Steps:
     *  1. Fake the notification.
     *  2. Create a user.
     *  3. Initialize Livewire test for 'auth.forgot-password' component.
     *  4. Set user email.
     *  5. Call 'sendResetPasswordEmailNotification' method.
     *  6. Assert that the reset password notification is sent to the user.
     *  7. Assert that the 'auth.reset-password' Livewire component is rendered.
     *  8. Set user email, password, and password confirmation.
     *  9. Call 'resetPassword' method.
     * 10. Assert redirection to login route.
     * 11. Assert no validation errors.
     */
    public function test_password_can_be_reset_with_valid_token(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        Livewire::test('auth.forgot-password')
            ->set('email', $user->email)
            ->call('sendResetPasswordEmailNotification');

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            Livewire::test('auth.reset-password', ['token' => $notification->token, 'email' => $user->email])
                ->set('email', $user->email)
                ->set('password', 'password')
                ->set('password_confirmation', 'password')
                ->call('resetPassword')
                ->assertRedirect(route('login'))
                ->assertHasNoErrors();

            return true;
        });
    }

    /**
     * Test: Password reset attempt with invalid data displays error message.
     *
     * Steps:
     *  1. Initialize Livewire test for 'auth.reset-password' component with invalid token.
     *  2. Set email, password, and password confirmation.
     *  3. Call 'resetPassword' method.
     *  4. Assert that an error message is displayed.
     */
    public function password_reset_attempt_with_invalid_data_displays_error_message(): void
    {
        Livewire::test('auth.reset-password', ['token' => 'test_token'])
            ->set('email', 'test@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('resetPassword');

        $this->assertArrayHasKey('message_failed', session()->all());
    }

    /**
     * Test: Password reset attempt with invalid token displays error message.
     *
     * Steps:
     *  1. Mock the Password facade to return an invalid token status.
     *  2. Initialize Livewire test for 'auth.reset-password' component with the invalid token.
     *  3. Set email, password, and password confirmation.
     *  4. Call 'resetPassword' method.
     *  5. Assert that an error message is displayed.
     */
    public function test_password_reset_attempt_with_invalid_token_displays_error_message(): void
    {
        $invalidToken = 'invalid_token';

        Password::shouldReceive('reset')->andReturn(Password::INVALID_TOKEN);

        Livewire::test('auth.reset-password', ['token' => $invalidToken])
            ->set('email', 'test@example.com')
            ->set('password', 'newpassword')
            ->set('password_confirmation', 'newpassword')
            ->call('resetPassword');

        $this->assertArrayHasKey('message_failed', session()->all());
    }

    /**
     * Test: Password reset attempt when throttled displays error message.
     *
     * Steps:
     *  1. Mock the Password facade to return a reset throttled status.
     *  2. Initialize Livewire test for 'auth.reset-password' component with the throttled token.
     *  3. Set email, password, and password confirmation.
     *  4. Call 'resetPassword' method.
     *  5. Assert that an error message is displayed.
     */
    public function test_password_reset_attempt_when_throttled_displays_error_message(): void
    {
        $throttledToken = 'throttled_token';

        Password::shouldReceive('reset')->andReturn(Password::RESET_THROTTLED);

        Livewire::test('auth.reset-password', ['token' => $throttledToken])
            ->set('email', 'test@example.com')
            ->set('password', 'newpassword')
            ->set('password_confirmation', 'newpassword')
            ->call('resetPassword');

        $this->assertArrayHasKey('message_failed', session()->all());
    }

    /**
     * Test: Password reset attempt with generic error displays error message.
     *
     * Steps:
     *  1. Mock the Password facade to return a generic error status.
     *  2. Initialize Livewire test for 'auth.reset-password' component.
     *  3. Set email, password, and password confirmation.
     *  4. Call 'resetPassword' method.
     *  5. Assert that an error message is displayed.
     */
    public function test_password_reset_attempt_with_generic_error_displays_error_message(): void
    {
        Password::shouldReceive('reset')->andReturn('unexpected_error_code');

        Livewire::test('auth.reset-password', ['token' => 'valid_token'])
            ->set('email', 'test@example.com')
            ->set('password', 'newpassword')
            ->set('password_confirmation', 'newpassword')
            ->call('resetPassword');

        $this->assertArrayHasKey('message_failed', session()->all());
    }

    /**
     * Test: Flash message for invalid user status is added.
     *
     * Steps:
     *  1. Mock the Password facade to return an invalid user status.
     *  2. Initialize Livewire test for 'auth.reset-password' component.
     *  3. Set email, password, and password confirmation.
     *  4. Call 'resetPassword' method.
     *  5. Assert redirection to login route.
     *  6. Assert no Livewire validation errors.
     *  7. Access the flashed message from the session.
     *  8. Assert that the flash message is set with the correct value.
     */
    public function test_flash_message_for_invalid_user_status_is_added()
    {
        // Mock the Password facade to return an invalid user status
        Password::shouldReceive('reset')->andReturn(Password::INVALID_USER);

        Livewire::test('auth.reset-password', ['token' => 'valid_token'])
            ->set('email', 'test@example.com')
            ->set('password', 'newpassword')
            ->set('password_confirmation', 'newpassword')
            ->call('resetPassword')
            ->assertRedirect(route('login'))
            ->assertHasNoErrors();

        // Access the flashed message from the session
        $flashMessage = session('message_failed');

        // Assert that the flash message is set with the correct value
        $this->assertEquals(__('passwords.user'), $flashMessage);
    }
}
