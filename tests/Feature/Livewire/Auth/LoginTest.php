<?php

namespace Tests\Feature\Livewire\Auth;

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Logout;
use App\Livewire\Home;
use App\Models\User;
use Illuminate\Auth\Events\Failed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Render the login component successfully.
     *
     * Steps:
     *  1. Arrange & Act: Render the login component
     *  2. Assert: Check that the response status is 200
     */
    public function test_renders_successfully()
    {
        Livewire::test(Login::class)
            ->assertStatus(200);
    }

    /**
     * Test: Check if the Livewire component exists on the page.
     *
     * Steps:
     *  1. Arrange & Act: Access the /login page and check if the Livewire component exists
     *  2. Assert: Ensure that the Livewire component is present on the page
     */
    public function test_component_exists_on_the_page()
    {
        Livewire::test(Login::class)
            ->assertSeeLivewire(Login::class);
    }

    /**
     * Test: User can set fields.
     *
     * Steps:
     *  1. Arrange & Act: Access the /login page and check if the user can set the required fields
     *  2. Assert: Ensure that fields can be set
     */
    public function test_user_can_set_fields()
    {
        // Arrange & Act: Access the /login page and check if user can set the required fields
        // Assert: Ensure that fields can be set
        Livewire::test('auth.login')
            ->set('identifier', 'testusername')
            ->assertSet('identifier', 'testusername')
            ->set('password', 'password')
            ->assertSet('password', 'password');
    }

    /**
     * Test: Login validation works.
     *
     * Steps:
     *  1. Test empty values, invalid email, and short password
     *  2. Test maximum length for identifier
     */
    public function test_login_validation_works()
    {
        Livewire::test('auth.login')
            ->set('identifier', '') // Test: Empty name
            ->set('password', '1') // Test: Short password
            ->call('login')
            ->assertHasErrors(['identifier', 'password']);

        Livewire::test('auth.login')
            ->set('identifier', str_repeat('b', 51)) // Test: Username exceeds maximum length
            ->set('password', 'validpassword')
            ->call('login')
            ->assertHasErrors(['identifier']);
    }

    /**
     * Test: User can login.
     *
     * Scenario: A user attempts to login with valid credentials, check if it will redirected and will see the successful login message.
     *
     * Steps:
     *  1. Access the /login page and check if the user can login
     *  2. Ensure that there are no validation errors and the component redirects to the home route
     */
    public function test_user_can_login()
    {
        $user = User::factory()->create();

        Livewire::test('auth.login')
            ->set('identifier', $user->email)
            ->set('password', 'password')
            ->call('login')
            ->assertHasNoErrors(['identifier', 'password'])
            ->assertRedirect(Home::class);

        $this->assertAuthenticated();
    }

    /**
     * Test: Failed login attempt event dispatches.
     *
     * Scenario: A user tries to login with an incorrect password, a failed login attempt event dispatches.
     *
     * Steps:
     *  1. Create a fake event to monitor dispatched events
     *  2. Create a user and attempt to log in with an incorrect password
     *  3. Ensure that the Failed event is dispatched during the failed login attempt
     */
    public function test_failed_login_attempt_event_dispatches()
    {
        Event::fake();

        $user = User::factory()->create();

        Livewire::test('auth.login')
            ->set('identifier', $user['email'])
            ->set('password', '123456')
            ->call('login');

        Event::assertDispatched(Failed::class);
    }

    /**
     * Test: User can login with a username.
     *
     * Scenario: A user attempts to login with it's username and everything working as expected.
     *
     * Steps:
     *  1. Create a user with a specific email address.
     *  2. Initialize Livewire test for the Login component.
     *  3. Set the identifier & the password correctly.
     *  4. Trigger the login method.
     *  5. Assert the authentication is successful, and a success message is flashed to the session.
     */
    public function test_user_can_login_with_username()
    {
        User::factory()->create(['username' => 'testusername']);

        Livewire::test(Login::class)
            ->set('identifier', 'testusername')
            ->set('password', 'password')
            ->call('login');

        $this->assertAuthenticated();
        $this->assertTrue(session()->exists('message'));
    }

    /**
     * Test: User can login with email.
     *
     * Scenario: A user attempts to login with it's email address and everything is working as expected.
     *
     * Steps:
     *  1. Create a user with a specific email address.
     *  2. Initialize Livewire test for the Login component.
     *  3. Set the identifier & the password correctly.
     *  4. Trigger the login method.
     *  5. Assert the authentication is successful, and a success message is flashed to the session.
     */
    public function test_user_can_login_with_email()
    {
        User::factory()->create(['email' => 'test@example.com']);

        Livewire::test(Login::class)
            ->set('identifier', 'test@example.com')
            ->set('password', 'password')
            ->call('login');

        $this->assertAuthenticated();
        $this->assertTrue(session()->exists('message'));
    }

    /**
     * Test: Application handles failed login.
     *
     * Scenario: Trying to log in to a non existing user and check if it's not authenticated.
     *
     * Steps:
     *  1. Initialize Livewire test for the Login component.
     *  2. Set the identifier & the password to an invalid value (non existing user).
     *  3. Trigger the login method.
     *  4. Assert that the user is not authenticated and the successful login message is not generated.
     */
    public function test_application_handles_failed_login()
    {
        Livewire::test(Login::class)
            ->set('identifier', 'invalid@example.com')
            ->set('password', 'invalidpassword')
            ->call('login');

        $this->assertGuest();
        $this->assertFalse(session()->exists('message'));
    }

    /**
     * Test: Throttling of too much login attempts.
     *
     * Scenario: Simulate multiple login attempts and check if throttling is working as expected.
     *
     * Steps:
     *  1. Initialize Livewire test for the Login component.
     *  2. Set the user identifier and a valid password.
     *  3. Trigger the login method multiple times.
     *  4. Assert that the expected throttle message is present in the Livewire component response.
     */
    public function test_component_throttles_login_attempts(): void
    {
        Livewire::test(Login::class)
            ->set('identifier', 'test@example.com')
            ->set('password', 'your_password') // Set a valid password
            ->call('login') // Trigger the login method
            ->call('login') // Additional attempt
            ->call('login') // Additional attempt
            ->call('login') // Additional attempt
            ->assertHasErrors('login');
    }

    /**
     * Test: User can not login with wrong password.
     *
     * Scenario: Simulate a user attempts to login with wrong password and check if everything working as expected.
     *
     * Steps:
     *  1. Create a user with factory.
     *  2. Initialize Livewire test for the Login component.
     *  3. Set the user identifier according to the created user.
     *  4. Set the user password to an invalid value.
     *  5. Trigger the login method.
     *  6. Assert that the error message is present in the Livewire component response and there is no redirect.
     *  7. Ensure the user is not authenticated.
     */
    public function test_user_can_not_login_with_wrong_password()
    {
        $user = User::factory()->create();

        Livewire::test('auth.login')
            ->set('identifier', $user->email)
            ->set('password', '123456')
            ->call('login')
            ->assertHasErrors()
            ->assertNoRedirect();

        $this->assertGuest();
    }

    /**
     * Test: User can logout from the application.
     *
     * Scenario: Simulate an authenticated user logging out.
     *
     * Steps:
     *  1. Create a user via factory.
     *  2. Initialize Livewire test for the Logout component acting as the created user.
     *  3. Trigger the logout method on the Logout component.
     *  4. Ensure that the component has no errors and redirects to the home route.
     *  5. Ensure the user is no longer authenticated after the logout.
     */
    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Logout::class)
            ->call('logout')
            ->assertHasNoErrors()
            ->assertRedirect(Home::class);

        $this->assertGuest();
    }
}
