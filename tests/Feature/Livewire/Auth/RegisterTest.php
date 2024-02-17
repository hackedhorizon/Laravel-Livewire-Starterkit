<?php

namespace Tests\Feature\Livewire\Auth;

use App\Livewire\Auth\Register;
use App\Livewire\Home;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_renders_successfully()
    {
        // Arrange & Act: Render the registration component
        // Assert: Check that the response status is 200
        Livewire::test(Register::class)
            ->assertStatus(200);
    }

    /** @test */
    public function test_component_exists_on_the_page()
    {
        // Arrange & Act: Access the /register page and check if the Livewire component exists
        // Assert: Ensure that the Livewire component is present on the page
        $this->get('/register')
            ->assertSeeLivewire(Register::class);
    }

    /** @test **/
    public function test_user_can_set_fields()
    {
        // Arrange & Act: Access the /register page and check if user can set the required fields
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

    /** @test **/
    public function test_register_validation_works()
    {
        // Test empty values, invalid email, and short password
        Livewire::test('auth.register')
            ->set('name', '') // Test: Empty name
            ->set('username', '') // Test: Empty username
            ->set('email', 'not_valid_email') // Test: Invalid email format
            ->set('password', '1') // Test: Short password
            ->call('store')
            ->assertHasErrors(['name', 'username', 'email', 'password']);

        // Test maximum length for name and username
        Livewire::test('auth.register')
            ->set('name', str_repeat('a', 51)) // Test: Name exceeds maximum length
            ->set('username', str_repeat('b', 31)) // Test: Username exceeds maximum length
            ->set('email', 'valid@email.com')
            ->set('password', 'validpassword')
            ->call('store')
            ->assertHasErrors(['name', 'username']);
    }

    /** @test **/
    public function test_user_can_register()
    {
        // Arrange & Act: Access the /register page and check if user can register
        // Assert: Ensure that there are no validation errors and the component redirects to the home route
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
