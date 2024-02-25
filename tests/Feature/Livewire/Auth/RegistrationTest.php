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

    /**
     * Test: Render the registration component successfully.
     *
     * Steps:
     *  1. Arrange & Act: Render the registration component
     *  2. Assert: Check that the response status is 200
     */
    public function test_renders_successfully()
    {
        Livewire::test(Register::class)
            ->assertStatus(200);
    }

    /**
     * Test: Check if the Livewire component exists on the page.
     *
     * Steps:
     *  1. Arrange & Act: Access the /register page and check if the Livewire component exists
     *  2. Assert: Ensure that the Livewire component is present on the page
     */
    public function test_component_exists_on_the_page()
    {
        $this->get('/register')
            ->assertSeeLivewire(Register::class);
    }

    /**
     * Test: User can set fields.
     *
     * Steps:
     *  1. Arrange & Act: Access the /register page and check if the user can set the required fields
     *  2. Assert: Ensure that fields can be set
     */
    public function test_user_can_set_fields()
    {
        // Arrange & Act: Access the /register page and check if the user can set the required fields
        // Assert: Ensure that fields can be set
        Livewire::test('auth.register')
            ->set('name', 'John Doe')
            ->assertSet('name', 'John Doe')
            ->set('username', 'johndoe')
            ->assertSet('username', 'johndoe')
            ->set('email', 'test@example.com')
            ->assertSet('email', 'test@example.com')
            ->set('password', 'password')
            ->assertSet('password', 'password');
    }

    /**
     * Test: Register validation works.
     *
     * Steps:
     *  1. Test empty values, invalid email, and short password
     *  2. Test maximum length for name and username
     */
    public function test_register_validation_works()
    {
        Livewire::test('auth.register')
            ->set('name', '') // Test: Empty name
            ->set('username', '') // Test: Empty username
            ->set('email', 'not_valid_email') // Test: Invalid email format
            ->set('password', '1') // Test: Short password
            ->call('store')
            ->assertHasErrors(['name', 'username', 'email', 'password']);

        Livewire::test('auth.register')
            ->set('name', str_repeat('a', 51)) // Test: Name exceeds maximum length
            ->set('username', str_repeat('b', 31)) // Test: Username exceeds maximum length
            ->set('email', 'valid@email.com')
            ->set('password', 'validpassword')
            ->call('store')
            ->assertHasErrors(['name', 'username']);
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
     *
     * Note: email is the same, therefore it is not required to test that, as well.
     */
    public function test_component_throttles_username_fetch_attempts(): void
    {
        Livewire::test(Register::class)
            ->set('username', 'test_username') // Username fetch attempt 1
            ->set('username', 'test_username') // Username fetch attempt 2
            ->set('username', 'test_username') // Username fetch attempt 3
            ->set('username', 'test_username') // Username fetch attempt 4
            ->set('username', 'test_username') // Username fetch attempt 5
            ->set('username', 'test_username') // Username fetch attempt 6
            ->set('username', 'test_username') // Username fetch attempt 7
            ->set('username', 'test_username') // Username fetch attempt 8
            ->set('username', 'test_username') // Username fetch attempt 9
            ->set('username', 'test_username') // Username fetch attempt 10
            ->set('username', 'test_username') // Username fetch attempt 11
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
     *  1. Arrange & Act: Access the /register page and check if the user can register
     *  2. Assert: Ensure that there are no validation errors and the component redirects to the home route
     */
    public function test_user_can_register()
    {
        Livewire::test('auth.register')
            ->set('name', 'user')
            ->set('username', 'cool_username')
            ->set('email', 'user@gmail.com')
            ->set('password', 'password')
            ->call('store')
            ->assertHasNoErrors(['name', 'username', 'email', 'password'])
            ->assertRedirect(Home::class);
    }
}
