<?php

namespace Tests\Feature\Livewire\Auth;

use App\Livewire\Auth\Register;
use App\Livewire\Home;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    const TEST_NAME = 'John Doe';
    const TEST_USERNAME = 'johndoe';
    const TEST_EMAIL = 'test@example.com';
    const TEST_PASSWORD = 'password';

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
     * ... (other tests remain unchanged)

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
            ->call('store')
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
            ->assertHasErrors('registration'); // Ensure username fetch attempts are throttled
    }

    /**
     * Test: Throttle registration attempts in the registration component.
     *
     * Scenario: Simulate multiple registration attempts and check if throttling is working as expected.
     *
     * Steps:
     *  1. Initialize Livewire test for the Register component.
     *  2. Trigger the store method multiple times exceeding the allowed attempts.
     *  3. Assert that the expected throttle message is present in the Livewire component response.
     */
    public function test_component_throttles_registration_attempts(): void
    {
        Livewire::test(Register::class)
            ->call('store') // Attempt 1
            ->call('store') // Attempt 2
            ->call('store') // Attempt 3
            ->call('store') // Attempt 4
            ->call('store') // Attempt 5
            ->call('store') // Attempt 6
            ->call('store') // Attempt 7
            ->call('store') // Attempt 8
            ->call('store') // Attempt 9
            ->call('store') // Attempt 10
            ->call('store') // Attempt 11
            ->assertHasErrors('registration'); // Ensure registration attempts are throttled
    }

    /**
     * Test: User can register.
     *
     * Steps:
     *  1. Access the /register page and check if the user can register
     *  2. Ensure that there are no validation errors and the component redirects to the home route
     */
    public function test_user_can_register()
    {
        Livewire::test(Register::class)
            ->set('name', self::TEST_NAME)
            ->set('username', self::TEST_USERNAME)
            ->set('email', self::TEST_EMAIL)
            ->set('password', self::TEST_PASSWORD)
            ->call('store')
            ->assertHasNoErrors(['name', 'username', 'email', 'password'])
            ->assertRedirect(Home::class);
    }
}
