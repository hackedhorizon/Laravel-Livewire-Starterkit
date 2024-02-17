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
        Livewire::test(Register::class)
            ->assertStatus(200);
    }

    /** @test */
    public function test_component_exists_on_the_page()
    {
        $this->get('/register')
            ->assertSeeLivewire(Register::class);
    }

    /** @test **/
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

    /** @test **/
    public function test_user_register_validation_works()
    {
        Livewire::test('auth.register')
            ->set('name', '')
            ->set('username', '')
            ->set('email', 'not_valid_email')
            ->set('password', '1')
            ->call('store')
            ->assertHasErrors(['name', 'username', 'email', 'password']);
    }
}
