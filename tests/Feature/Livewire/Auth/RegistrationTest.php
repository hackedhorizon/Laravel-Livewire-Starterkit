<?php

namespace Tests\Feature\Livewire\Auth;

use App\Livewire\Auth\EmailVerification;
use App\Livewire\Auth\Register;
use App\Livewire\Home;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
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
        Livewire::test(Register::class)
            ->set('username', self::TEST_USERNAME) // Username fetch attempt 1
            ->set('username', self::TEST_USERNAME) // Username fetch attempt 2
            ->set('username', self::TEST_USERNAME) // Username fetch attempt 3
            ->set('username', self::TEST_USERNAME) // Username fetch attempt 4
            ->set('username', self::TEST_USERNAME) // Username fetch attempt 5
            ->set('username', self::TEST_USERNAME) // Username fetch attempt 6
            ->set('username', self::TEST_USERNAME) // Username fetch attempt 7
            ->set('username', self::TEST_USERNAME) // Username fetch attempt 8
            ->set('username', self::TEST_USERNAME) // Username fetch attempt 9
            ->set('username', self::TEST_USERNAME) // Username fetch attempt 10
            ->set('username', self::TEST_USERNAME) // Username fetch attempt 11
            ->assertHasErrors('register'); // Ensure username fetch attempts are throttled
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
        Livewire::test(Register::class)
            ->call('register') // Attempt 1
            ->call('register') // Attempt 2
            ->call('register') // Attempt 3
            ->call('register') // Attempt 4
            ->call('register') // Attempt 5
            ->call('register') // Attempt 6
            ->call('register') // Attempt 7
            ->call('register') // Attempt 8
            ->call('register') // Attempt 9
            ->call('register') // Attempt 10
            ->call('register') // Attempt 11
            ->assertHasErrors('register'); // Ensure registration attempts are throttled
    }

    /**
     * Test: User can register.
     *
     * Steps:
     *  1. Check if recaptcha is enabled in the config file. If yes, we skip this test.
     *  2. Initialize Livewire test for the Register component with valid user data.
     *  3. Call the 'register' method.
     *  4. Assert that there are no validation errors for name, username, email, and password.
     *  5. Assert that the user is redirected to the Home component.
     *  6. Additional assertions can be added, such as checking that the user was created in the database.
     */
    public function test_user_can_register()
    {
        if (config('services.should_have_recaptcha')) {
            $this->markTestSkipped('Recaptcha is enabled in the configuration.');
        }

        if (config('services.should_verify_email')) {
            Livewire::test(Register::class)
                ->set('name', self::TEST_NAME)
                ->set('username', self::TEST_USERNAME)
                ->set('email', self::TEST_EMAIL)
                ->set('password', self::TEST_PASSWORD)
                ->call('register')
                ->assertHasNoErrors(['name', 'username', 'email', 'password'])
                ->assertRedirect(EmailVerification::class);
        } else {
            Livewire::test(Register::class)
                ->set('name', self::TEST_NAME)
                ->set('username', self::TEST_USERNAME)
                ->set('email', self::TEST_EMAIL)
                ->set('password', self::TEST_PASSWORD)
                ->call('register')
                ->assertHasNoErrors(['name', 'username', 'email', 'password'])
                ->assertRedirect(Home::class);
        }
    }

    /**
     * Test: User can register with a valid Recaptcha token.
     *
     * Steps:
     *  1. Check if recaptcha is enabled in the config file. If it is not enabled, we skip this test.
     *  2. Mock the RecaptchaService response to always return success for testing purposes.
     *  3. Initialize Livewire test for the Register component with valid user data and Recaptcha token.
     *  4. Call the 'register' method.
     *  5. Assert that there are no validation errors for name, username, email, and password.
     *  6. Assert that the user is redirected to the Home component.
     *  7. Additional assertions can be added, such as checking that the user was created in the database.
     */
    public function test_user_can_register_with_valid_recaptcha()
    {
        if (! config('services.should_have_recaptcha')) {
            $this->markTestSkipped('Recaptcha is not enabled in the configuration.');
        }

        // Mock the RecaptchaService response to always return success for testing purposes
        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify*' => Http::response(['success' => true, 'score' => 0.9]),
        ]);

        if (config('services.should_verify_email')) {
            Livewire::test(Register::class)
                ->set('name', self::TEST_NAME)
                ->set('username', self::TEST_USERNAME)
                ->set('email', self::TEST_EMAIL)
                ->set('password', self::TEST_PASSWORD)
                ->set('recaptchaToken', self::TEST_RECAPTCHA_TOKEN)
                ->call('register')
                ->assertHasNoErrors(['name', 'username', 'email', 'password'])
                ->assertRedirect(EmailVerification::class);
        } else {
            Livewire::test(Register::class)
                ->set('name', self::TEST_NAME)
                ->set('username', self::TEST_USERNAME)
                ->set('email', self::TEST_EMAIL)
                ->set('password', self::TEST_PASSWORD)
                ->set('recaptchaToken', self::TEST_RECAPTCHA_TOKEN)
                ->call('register')
                ->assertHasNoErrors(['name', 'username', 'email', 'password'])
                ->assertRedirect(Home::class);
        }

        // Check the user was created
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
     *  1. Check if recaptcha is enabled in the config file. If it is not enabled, we skip this test.
     *  2. Mock the RecaptchaService response to always return failure for testing purposes.
     *  3. Initialize Livewire test for the Register component with valid user data and an invalid Recaptcha token.
     *  4. Call the 'register' method.
     *  5. Assert that there are no validation errors for name, username, email, and password.
     *  6. Assert that there is a validation error for the Recaptcha field.
     *  7. Assert that the user is not redirected.
     *  8. Check that the user was not created in the database.
     */
    public function test_user_can_not_register_with_invalid_recaptcha()
    {
        if (! config('services.should_have_recaptcha')) {
            $this->markTestSkipped('Recaptcha is not enabled in the configuration.');
        }

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
}
