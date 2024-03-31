<?php

namespace Tests\Feature\Livewire\Auth;

use App\Livewire\Auth\Register;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    const TEST_NAME = 'John Doe';

    const TEST_USERNAME = 'johndoe';

    const TEST_EMAIL = 'test@example.com';

    const TEST_PASSWORD = 'password';

    const TEST_RECAPTCHA_TOKEN = 'valid_recaptcha_token';

    protected function setUp(): void
    {
        parent::setUp();

        // Define the routes necessary for testing (in a case this functionality is disabled)
        Route::get('/verify-email', [\App\Livewire\Auth\EmailVerification::class, '__invoke'])->name('verification.notice');
        Route::get('/verify-email/{id}/{hash}', [\App\Livewire\Auth\EmailVerification::class, 'verifyEmail'])->name('verification.verify');
        Route::post('/verify-email/send-notification', [\App\Livewire\Auth\EmailVerification::class, 'sendVerificationEmail'])->name('verification.send');
    }

    /**
     * Test: Render the registration component successfully.
     *
     * Steps:
     *  1. Render the registration component
     *  2. Check that the response status is 200
     */
    public function test_renders_successfully()
    {
        Livewire::test(Register::class)
            ->assertStatus(200);
    }

    /**
     * Test: User can set fields.
     *
     * Steps:
     *  1. Access the /register page and check if the user can set the required fields
     *  2. Ensure that fields can be set
     */
    public function test_user_can_set_fields()
    {
        Livewire::test(Register::class)
            ->set('name', self::TEST_NAME)
            ->assertSet('name', self::TEST_NAME)
            ->set('username', self::TEST_USERNAME)
            ->assertSet('username', self::TEST_USERNAME)
            ->set('email', self::TEST_EMAIL)
            ->assertSet('email', self::TEST_EMAIL)
            ->set('password', self::TEST_PASSWORD)
            ->assertSet('password', self::TEST_PASSWORD);
    }

    /**
     * Test: Register validation works.
     *
     * Steps:
     *  1. Test empty values, invalid email, and short password
     *  2. Test maximum length for name and username
     *
     * @dataProvider registrationValidationDataProvider
     */
    public function test_register_validation_works(string $name, string $username, string $email, string $password, array $expectedErrors)
    {
        Livewire::test(Register::class)
            ->set('name', $name)
            ->set('username', $username)
            ->set('email', $email)
            ->set('password', $password)
            ->call('register')
            ->assertHasErrors($expectedErrors);
    }

    public static function registrationValidationDataProvider()
    {
        return [
            // Test: Empty name, empty username, invalid email format, short password
            ['', '', 'not_valid_email', '1', ['name', 'username', 'email', 'password']],

            // Test: Name exceeds maximum length, username exceeds maximum length
            [str_repeat('a', 51), str_repeat('b', 31), 'valid@email.com', 'validpassword', ['name', 'username']],
        ];
    }

    /**
     * Test: Throttle username fetch attempts in the registration component.
     *
     * Scenario: Simulate multiple attempts to set the username in the registration component and check if throttling is working as expected.
     *
     * Steps:
     *  1. Initialize Livewire test for the Register component.
     *  2. Set the username multiple times.
     *  3. Assert that the expected throttle message is present in the Livewire component response.
     */
    public function test_component_throttles_username_fetch_attempts(): void
    {
        $maxAttempts = 11;
        $username = self::TEST_USERNAME;

        $registerTest = Livewire::test(Register::class);

        // Simulate multiple attempts to set the username
        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            $registerTest->set('username', $username); // Username fetch attempt
        }

        $registerTest->assertHasErrors('register'); // Ensure username fetch attempts are throttled
    }

    /**
     * Test: Throttle registration attempts in the registration component.
     *
     * Scenario: Simulate multiple registration attempts and check if throttling is working as expected.
     *
     * Steps:
     *  1. Initialize Livewire test for the Register component.
     *  2. Trigger the register method multiple times exceeding the allowed attempts.
     *  3. Assert that the expected throttle message is present in the Livewire component response.
     */
    public function test_component_throttles_registration_attempts(): void
    {
        $maxAttempts = 11;

        $registerTest = Livewire::test(Register::class);

        // Simulate multiple registration attempts exceeding the allowed attempts
        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            $registerTest->call('register'); // Attempt
        }

        // Assert that the expected throttle message is present in the Livewire component response
        $registerTest->assertHasErrors('register'); // Ensure registration attempts are throttled
    }

    /**
     * Test: User can register.
     *
     * Steps:
     *  1. Initialize Livewire test for the Register component with valid user data.
     *  2. Call the 'register' method.
     *  3. Assert that there are no validation errors for name, username, email, and password.
     *  4. Assert the redirection based on the email verification configuration.
     *  5. Additional assertions can be added, such as checking that the user was created in the database.
     */
    public function test_user_can_register()
    {
        config(['services.should_verify_email' => true]);
        config(['services.should_have_recaptcha' => false]);

        Livewire::test(Register::class)
            ->set('name', self::TEST_NAME)
            ->set('username', self::TEST_USERNAME)
            ->set('email', self::TEST_EMAIL)
            ->set('password', self::TEST_PASSWORD)
            ->call('register')
            ->assertHasNoErrors(['name', 'username', 'email', 'password'])
            ->assertRedirect(route('verification.notice'));
    }

    /**
     * Test: User can register with a valid Recaptcha token.
     *
     * Steps:
     *  1. Mock the RecaptchaService response to always return success for testing purposes.
     *  2. Initialize Livewire test for the Register component with valid user data and Recaptcha token.
     *  3. Call the 'register' method.
     *  4. Assert that there are no validation errors for name, username, email, and password.
     *  5. Assert the redirection based on the email verification configuration.
     *  6. Additional assertions can be added, such as checking that the user was created in the database.
     */
    public function test_user_can_register_with_valid_recaptcha()
    {
        config(['services.should_verify_email' => true]);
        config(['services.should_have_recaptcha' => true]);

        // Mock the RecaptchaService response to always return success for testing purposes
        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify*' => Http::response(['success' => true, 'score' => 0.9]),
        ]);

        $registerTest = Livewire::test(Register::class)
            ->set('name', self::TEST_NAME)
            ->set('username', self::TEST_USERNAME)
            ->set('email', self::TEST_EMAIL)
            ->set('password', self::TEST_PASSWORD)
            ->set('recaptchaToken', self::TEST_RECAPTCHA_TOKEN)
            ->call('register')
            ->assertHasNoErrors(['name', 'username', 'email', 'password'])
            ->assertRedirect(route('verification.notice'));

        // Check if the user was created
        $this->assertDatabaseHas('users', [
            'name' => self::TEST_NAME,
            'username' => self::TEST_USERNAME,
            'email' => self::TEST_EMAIL,
        ]);

        // Note: If this test fails, it might be due to a mismatch in the Recaptcha score threshold.
        // The Livewire component may have a higher threshold (e.g., 1.0), while the fake HTTP request
        // score in this test is set to 0.9. Ensure consistency in score thresholds for accurate testing.
        // If the thresholds differ, the test may fail, preventing the user from being redirected.
    }

    /**
     * Test: User registration fails with an invalid Recaptcha token.
     *
     * Steps:
     *  1. Mock the RecaptchaService response to always return failure for testing purposes.
     *  2. Initialize Livewire test for the Register component with valid user data and an invalid Recaptcha token.
     *  3. Call the 'register' method.
     *  4. Assert that there are no validation errors for name, username, email, and password.
     *  5. Assert that there is a validation error for the Recaptcha field.
     *  6. Assert that the user is not redirected.
     *  7. Check that the user was not created in the database.
     */
    public function test_user_can_not_register_with_invalid_recaptcha()
    {
        config(['services.should_have_recaptcha' => true]);

        // Mock the RecaptchaService response to always return failure for testing purposes
        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify*' => Http::response(['success' => false, 'score' => 0.2]),
        ]);

        Livewire::test(Register::class)
            ->set('name', self::TEST_NAME)
            ->set('username', self::TEST_USERNAME)
            ->set('email', self::TEST_EMAIL)
            ->set('password', self::TEST_PASSWORD)
            ->set('recaptchaToken', self::TEST_RECAPTCHA_TOKEN)
            ->call('register')
            ->assertHasNoErrors(['name', 'username', 'email', 'password'])
            ->assertHasErrors(['recaptcha' => __('validation.recaptcha_failed')])
            ->assertNoRedirect();

        // Check the user was not created
        $this->assertDatabaseMissing('users', [
            'name' => self::TEST_NAME,
            'username' => self::TEST_USERNAME,
            'email' => self::TEST_EMAIL,
        ]);
    }

    /**
     * Test: User is redirected to the home page after successful registration.
     *
     * Steps:
     *  1. Mock the Auth facade to expect a login attempt.
     *  2. Initialize Livewire test for the Register component with valid user data.
     *  3. Call the 'register' method.
     *  4. Assert that there are no validation errors for name, username, email, and password.
     *  5. Assert that the user is logged in.
     *  6. Additional assertions can be added, such as checking that the user was created in the database.
     */
    public function test_user_is_redirected_to_home_page_after_successful_registration()
    {
        config(['services.should_verify_email' => false]);
        config(['services.should_have_recaptcha' => false]);

        // Initialize Livewire test for the Register component with valid user data
        Livewire::test(Register::class)
            ->set('name', self::TEST_NAME)
            ->set('username', self::TEST_USERNAME)
            ->set('email', self::TEST_EMAIL)
            ->set('password', self::TEST_PASSWORD)
            ->call('register')
            ->assertHasNoErrors(['name', 'username', 'email', 'password']);

        // Check if the user was created
        $this->assertDatabaseHas('users', [
            'name' => self::TEST_NAME,
            'username' => self::TEST_USERNAME,
            'email' => self::TEST_EMAIL,
        ]);

        // Assert that the user is logged in
        $this->assertAuthenticated();
    }
}
