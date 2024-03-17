<?php

namespace Tests\Feature\Livewire\Features;

use App\Http\Middleware\SetLocale;
use App\Livewire\Features\LanguageSwitcher;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Livewire\Livewire;
use Tests\TestCase;

class LanguageSwitcherTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_sets_locale_based_on_user_language_when_authenticated()
    {
        $user = User::factory()->create(['language' => 'en']);

        Auth::login($user);

        $middleware = new SetLocale();

        $request = Request::create('/');
        $middleware->handle($request, function ($req) {
            // Assert that locale is set to user language
            $this->assertEquals('en', app()->getLocale());
        });
    }

    /** @test */
    public function it_sets_locale_based_on_session_locale_when_not_authenticated()
    {
        Config::set('app.locale', 'en'); // Set fallback locale to 'en'
        Session::put('locale', 'es'); // Set session locale to 'es'

        $middleware = new SetLocale();

        $request = Request::create('/');
        $middleware->handle($request, function ($req) {
            // Assert that locale is set to session locale
            $this->assertEquals('es', app()->getLocale());
        });
    }

    /** @test */
    public function it_sets_locale_to_fallback_when_no_user_or_session_locale()
    {
        Config::set('app.locale', 'en'); // Set fallback locale to 'en'

        $middleware = new SetLocale();

        $request = Request::create('/');
        $middleware->handle($request, function ($req) {
            // Assert that locale is set to fallback locale
            $this->assertEquals('en', app()->getLocale());
        });
    }
}
