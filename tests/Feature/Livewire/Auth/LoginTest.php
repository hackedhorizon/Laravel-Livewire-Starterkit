<?php

namespace Tests\Feature\Livewire\Auth;

use App\Livewire\Auth\Login;
use App\Livewire\Home;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LoginTest extends TestCase
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

    // TODO:
    // - wrong password & logout ability testing
}
