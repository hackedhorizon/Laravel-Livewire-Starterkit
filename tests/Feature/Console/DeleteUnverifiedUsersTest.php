<?php

namespace Tests\Feature\Console;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class DeleteUnverifiedUsersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Delete unverified users older than expiration date.
     *
     * Steps:
     *  1. Create an unverified user older than the expiration date.
     *  2. Run the command to delete unverified users.
     *  3. Assert that the user has been deleted from the database.
     */
    public function test_unverified_users_deleted()
    {
        $unverifiedUser = User::factory()->create([
            'email_verified_at' => null,
            'created_at' => now()->subDays(config('auth.verification.expire', 7) + 1), // Older than expiration date
        ]);

        Artisan::call('app:delete-unverified-users');

        $this->assertDatabaseMissing('users', ['id' => $unverifiedUser->id]);
    }

    /**
     * Test: Command does not run when email verification is disabled.
     *
     * Steps:
     *  1. Disable email verification.
     *  2. Create an unverified user.
     *  3. Run the command to delete unverified users.
     *  4. Assert that the user still exists.
     */
    public function test_command_does_not_run_without_email_verification()
    {
        config(['services.should_verify_email' => false]);

        User::factory()->create(['email_verified_at' => null]);

        Artisan::call('app:delete-unverified-users');

        $this->assertDatabaseCount('users', 1);
    }
}
