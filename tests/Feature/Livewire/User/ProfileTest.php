<?php

namespace Tests\Feature\Livewire\User;

use App\Livewire\User\Profile;
use App\Models\User;
use App\Modules\Registration\Services\EmailVerificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    const TEST_NAME = 'John Doe';

    const TEST_USERNAME = 'johndoe';

    const TEST_EMAIL = 'test@example.com';

    const TEST_PASSWORD = 'password';

    protected function setUp(): void
    {
        parent::setUp();

        // Define the routes necessary for testing (in case this functionality is disabled)
        Route::get('/verify-email', [\App\Livewire\Auth\EmailVerification::class, '__invoke'])->name('verification.notice');
        Route::get('/verify-email/{id}/{hash}', [\App\Livewire\Auth\EmailVerification::class, 'verifyEmail'])->name('verification.verify');
        Route::post('/verify-email/send-notification', [\App\Livewire\Auth\EmailVerification::class, 'sendVerificationEmail'])->name('verification.send');
    }

    /**
     * Test: Renders the profile component successfully.
     *
     * Steps:
     *  1. Render the profile component.
     *  2. Check that the response status is 200.
     */
    public function renders_successfully()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        Livewire::test(Profile::class)
            ->assertStatus(200);
    }

    /**
     * Test: User can update profile information with email verification.
     *
     * Steps:
     *  1. Set up the test environment with email verification enabled.
     *  2. Initialize Livewire test for the Profile component with valid user data.
     *  3. Call the 'updateProfileInformation' method.
     *  4. Assert that the user's profile information is updated correctly.
     *  5. Assert that the success flash message is set.
     */
    public function test_user_can_update_profile_information_with_email_verification()
    {
        config(['services.should_verify_email' => true]);

        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Profile::class)
            ->set('name', self::TEST_NAME)
            ->set('username', self::TEST_USERNAME)
            ->set('email', self::TEST_EMAIL)
            ->set('password', self::TEST_PASSWORD)
            ->call('updateProfileInformation');

        $this->assertDatabaseHas('users', [
            'name' => self::TEST_NAME,
            'username' => self::TEST_USERNAME,
            'temporary_email' => self::TEST_EMAIL,
        ]);

        $this->assertTrue(session()->has('message_success')); // Check if success flash message is set
    }

    /**
     * Test: User can update profile information without email verification.
     *
     * Steps:
     *  1. Initialize Livewire test for the Profile component with valid user data.
     *  2. Call the 'updateProfileInformation' method.
     *  3. Assert that the user's profile information is updated correctly.
     *  4. Assert that the success flash message is set.
     */
    public function test_user_can_update_profile_information_without_email_verification()
    {
        $user = User::factory()->create();

        config(['services.should_verify_email' => false]);

        Livewire::actingAs($user)
            ->test(Profile::class)
            ->set('name', self::TEST_NAME)
            ->set('username', self::TEST_USERNAME)
            ->set('email', self::TEST_EMAIL)
            ->set('password', self::TEST_PASSWORD)
            ->call('updateProfileInformation');

        $this->assertDatabaseHas('users', [
            'name' => self::TEST_NAME,
            'username' => self::TEST_USERNAME,
            'email' => self::TEST_EMAIL,
        ]);

        $this->assertTrue(session()->has('message_success')); // Check if success flash message is set
    }

    /**
     * Test: User email is updated after verification.
     *
     * Steps:
     *  1. Create a user with an old email and a temporary new email.
     *  2. Simulate the user updating their email in the Profile component.
     *  3. Assert that the user's email is updated correctly.
     *  4. Assert that the temporary email is cleared.
     *  5. Assert that the user's email is verified.
     */
    public function user_email_is_updated_after_verification()
    {
        // Arrange
        $user = User::factory()->create([
            'email' => 'old@example.com',
            'temporary_email' => 'new@example.com',
        ]);

        // Act
        Livewire::actingAs($user)
            ->test('auth.profile')
            ->set('email', 'new@example.com') // Simulate updating email
            ->call('updateProfileInformation');

        // Assert
        $this->verifyEmailAndUpdate($user, app(EmailVerificationService::class));

        // Verify email is updated correctly
        $this->assertEquals('new@example.com', $user->fresh()->email);
        $this->assertNull($user->fresh()->temporary_email);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    /**
     * Simulate email verification and update email.
     *
     * @return void
     */
    private function verifyEmailAndUpdate(User $user, EmailVerificationService $emailVerificationService)
    {
        $emailVerificationService->setUser($user);

        // Compute the hash for comparison
        $hash = sha1($user->getEmailForVerification());

        // Verify email with the correct hash
        $emailVerificationService->verifyEmail($user->id, $hash); // Simulate user clicking on verification link
    }

    /**
     * Test: User can delete itself.
     *
     * Steps:
     *  1. Initialize Livewire test for the Profile component.
     *  2. Call the 'deleteUser' method.
     *  3. Assert that the user is deleted from the database.
     */
    public function test_user_can_delete_itself()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Profile::class)
            ->call('deleteUser');

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
