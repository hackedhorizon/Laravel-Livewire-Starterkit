<?php

namespace Tests\Feature\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $this->get('/forgot-password')
            ->assertSeeLivewire('auth.forgot-password')
            ->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        Livewire::test('auth.forgot-password')
            ->set('email', $user->email)
            ->call('sendResetPasswordEmailNotification');

        Notification::assertSentTo($user, ResetPassword::class);
    }

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

    /** @test */
    public function password_reset_attempt_with_invalid_data_displays_error_message(): void
    {
        // Create a Livewire test for the password reset component
        Livewire::test('auth.reset-password', ['token' => 'test_token'])
            ->set('email', 'test@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('resetPassword');

        $this->assertArrayHasKey('message_failed', session()->all());
    }
}
