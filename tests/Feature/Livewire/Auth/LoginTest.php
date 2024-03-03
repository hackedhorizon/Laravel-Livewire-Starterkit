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

    const TEST_USERNAME = 'testusername';

    const TEST_PASSWORD = 'password';

    const TEST_EMAIL = 'test@email.com';

    /**
     * Test: Render the login component successfully.
     *
     * Steps:
     *  1. Render the login component
     *  2. Check that the response status is 200
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
     *  1. Access the /login page and check if the Livewire component exists
     *  2. Ensure that the Livewire component is present on the page
     */
    public function test_user_can_view_login_page()
    {
        $this->get(route('login'))
            ->assertSuccessful();
    }

    /**
     * Test: User can set fields.
     *
     * Steps:
     *  1. Access the /login page and check if the user can set the required fields
     *  2. Ensure that fields can be set
     */
    public function test_user_can_set_fields()
    {
        Livewire::test('auth.login')
            ->set('identifier', self::TEST_USERNAME)
            ->assertSet('identifier', self::TEST_USERNAME)
            ->set('password', self::TEST_PASSWORD)
            ->assertSet('password', self::TEST_PASSWORD);
    }

    /**
     * Test: Make sure the login validation works.
     *
     * Steps:
     *  1. Test empty value for name and email, too short password
     *  2. Test maximum length for identifier
     *
     * @dataProvider loginValidationDataProvider
     */
    public function test_login_validation_works(string $identifier, string $password, array $expectedErrors)
    {
        Livewire::test('auth.login')
            ->set('identifier', $identifier)
            ->set('password', $password)
            ->call('login')
            ->assertHasErrors($expectedErrors);
    }

    public static function loginValidationDataProvider()
    {
        return [
            // Test: Invalid name, invalid email, invalid password
            ['', '1', ['identifier', 'password']],

            // Test: Username exceeds maximum length
            [str_repeat('b', 51), self::TEST_PASSWORD, ['identifier']],
        ];
    }

    /**
     * Test: User can login.
     *
     * Scenario: A user attempts to login with valid credentials, check if it will redirected and will see the successful login message.
     *
     * Steps:
     *  1. Initialize Livewire test for the Login component.
     *  3. Set the identifier & the password correctly.
     *  4. Trigger the login method.
     *  5. Ensure that there are no validation errors and the component redirects to the home route
     *  5. Assert the authentication was successful, and a success message is flashed to the session.
     *
     * @dataProvider loginDataProvider
     */
    public function test_user_can_login(string $identifier, string $password)
    {
        // Create a user with default values
        User::factory()->create([
            'username' => self::TEST_USERNAME,
            'email' => self::TEST_EMAIL,
            'password' => self::TEST_PASSWORD,
        ]);

        // Try to login via identifier (either username or email -> data provider)
        Livewire::test(Login::class)
            ->set('identifier', $identifier)
            ->set('password', $password)
            ->call('login')
            ->assertHasNoErrors(['identifier', 'password'])
            ->assertRedirect(Home::class);

        $this->assertAuthenticated();

        $this->assertTrue(session()->exists('message_success'));
    }

    public static function loginDataProvider()
    {
        return [
            [self::TEST_USERNAME, self::TEST_PASSWORD],
            [self::TEST_EMAIL, self::TEST_PASSWORD],
        ];
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
     * Test: Application handles failed login.
     *
     * Scenario: Trying to log in to a non existing user and ensure it is not authenticated.
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
     *  2. Set the user identifier and a password.
     *  3. Trigger the login method multiple times.
     *  4. Assert that the expected throttle message is present in the Livewire component response.
     */
    public function test_component_throttles_login_attempts(): void
    {
        Livewire::test(Login::class)
            ->set('identifier', self::TEST_EMAIL)
            ->set('password', self::TEST_PASSWORD)
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
