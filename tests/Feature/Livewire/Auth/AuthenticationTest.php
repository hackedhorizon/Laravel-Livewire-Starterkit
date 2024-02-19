<?php

namespace Tests\Feature\Livewire\Auth;

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Logout;
use App\Livewire\Home;
use App\Models\User;
use App\Modules\Auth\Services\AuthService;
use Illuminate\Auth\Events\Failed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_renders_successfully()
    {
        // Arrange & Act: Render the login component
        // Assert: Check that the response status is 200
        Livewire::test(Login::class)
            ->assertStatus(200);
    }

    /** @test */
    public function test_component_exists_on_the_page()
    {
        // Arrange & Act: Access the /login page and check if the Livewire component exists
        // Assert: Ensure that the Livewire component is present on the page
        $this->get('/login')
            ->assertSeeLivewire(Login::class);
    }

    /** @test **/
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

    /** @test **/
    public function test_login_validation_works()
    {
        // Test empty values, invalid email, and short password
        Livewire::test('auth.login')
            ->set('identifier', '') // Test: Empty name
            ->set('password', '1') // Test: Short password
            ->call('login')
            ->assertHasErrors(['identifier', 'password']);

        // Test maximum length for identifier
        Livewire::test('auth.login')
            ->set('identifier', str_repeat('b', 51)) // Test: Username exceeds maximum length
            ->set('password', 'validpassword')
            ->call('login')
            ->assertHasErrors(['identifier']);
    }

    /** @test **/
    public function test_user_can_login()
    {
        // Arrange & Act: Access the /login page and check if user can login
        // Assert: Ensure that there are no validation errors and the component redirects to the home route

        $user = User::factory()->create();

        Livewire::test('auth.login')
            ->set('identifier', $user->email)
            ->set('password', 'password')
            ->call('login')
            ->assertHasNoErrors(['identifier', 'password'])
            ->assertRedirect(Home::class);

        $this->assertAuthenticated();
    }

    /** @test **/
    public function test_failed_login_attempt_event_dispatches()
    {
        // Arrange: Fake the event system to monitor dispatched events
        // Act: Create a user and attempt to log in with an incorrect password
        // Assert: Ensure that the Failed event is dispatched during the failed login attempt
        Event::fake();

        Livewire::test('auth.login')
            ->set('identifier', 'random@user.com')
            ->set('password', '123456')
            ->call('login');

        // Assert that the Failed event is dispatched
        Event::assertDispatched(Failed::class);
    }

    /** @test */
    public function test_it_attempts_login_with_username()
    {
        // Arrange: Create an instance of the AuthService
        // Act: Simulate a Livewire test scenario where a user attempts login with a username
        // Assert: Verify that authentication is successful, and a success message is flashed to the session
        $authService = new AuthService;

        $user = User::factory()->create(['username' => 'testusername']);

        Livewire::test(Login::class)
            ->set('identifier', 'testusername')
            ->set('password', 'password')
            ->call('login');

        $this->assertTrue(Auth::check());
        $this->assertTrue(session()->exists('message'));
    }

    /** @test */
    public function test_it_attempts_login_with_email()
    {
        // Arrange: Create an instance of the AuthService
        // Act: Simulate a test scenario where a user attempts login with an email
        // Assert: Verify that authentication is successful, and a success message is flashed to the session
        $authService = new AuthService;

        $user = User::factory()->create(['email' => 'test@example.com']);

        Livewire::test(Login::class)
            ->set('identifier', 'test@example.com')
            ->set('password', 'password')
            ->call('login');

        $this->assertTrue(Auth::check());
        $this->assertTrue(session()->exists('message'));
    }

    /** @test */
    public function test_it_handles_failed_login()
    {
        // Arrange: Create an instance of the AuthService
        // Act: Simulate a test scenario where a user attempts login with invalid credentials
        // Assert: Verify that authentication fails, and no success message is present in the session
        $authService = new AuthService;

        Livewire::test(Login::class)
            ->set('identifier', 'invalid@example.com')
            ->set('password', 'invalidpassword')
            ->call('login');

        $this->assertFalse(Auth::check());
        $this->assertFalse(session()->exists('message'));
    }

    /** @test **/
    public function test_user_can_not_login_with_wrong_password()
    {
        // Arrange: Create a user and attempt to log in with an incorrect password
        // Act: Set the user's email and a wrong password, and call the login method
        // Assert: Ensure that the login attempt fails, errors are present, and no redirection occurs
        $user = User::factory()->create();

        Livewire::test('auth.login')
            ->set('identifier', $user->email)
            ->set('password', '123456')
            ->call('login')
            ->assertHasErrors()
            ->assertNoRedirect();

        // Ensure the user is not authenticated after the failed login attempt
        $this->assertGuest();
    }

    /** @test **/
    public function test_user_can_logout()
    {
        // Arrange: Create a user and simulate authentication
        // Act: Log the user out using the Logout Livewire component
        // Assert: Ensure that the logout process has no errors and redirects to the home route
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Logout::class)
            ->call('logout')
            ->assertHasNoErrors()
            ->assertRedirect(Home::class);

        // Ensure the user is no longer authenticated after the logout
        $this->assertGuest();
    }
}
