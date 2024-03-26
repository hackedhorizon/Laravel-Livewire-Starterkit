<?php

namespace Tests\Feature\Livewire\Features;

use App\Livewire\Features\LanguageSwitcher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Livewire\Livewire;
use Tests\TestCase;

class LanguageSwitcherTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Component renders correctly.
     *
     * Steps:
     *  1. Render the LanguageSwitcher component.
     *  2. Assert that the component contains the text for selecting a language.
     */

    /** @test */
    public function component_renders_correctly()
    {
        Livewire::test(LanguageSwitcher::class)
            ->assertSee(__('actions.select_a_language'));
    }

    /**
     * Test: User can select a language.
     *
     * Steps:
     *  1. Generate a random locale.
     *  2. Set the selected language in the LanguageSwitcher component.
     *  3. Assert that the selected language is stored in the session.
     *  4. Assert that the user is redirected to the home page.
     *  5. Assert that the user is not authenticated.
     */
    /** @test */
    public function user_can_select_language()
    {
        $randomLocale = $this->getRandomLocale();

        Livewire::test(LanguageSwitcher::class)
            ->set('selectedLanguage', $randomLocale)
            ->assertSessionHas('locale', $randomLocale)
            ->assertRedirect('/');

        $this->assertGuest();
    }

    /**
     * Test: Authenticated user can select a language and update user language in the database.
     *
     * Steps:
     *  1. Generate a random locale.
     *  2. Create a user with an empty language attribute.
     *  3. Act as the user and set the selected language in the LanguageSwitcher component.
     *  4. Assert that the selected language is stored in the session.
     *  5. Assert that the user's language attribute in the database is updated.
     */
    /** @test */
    public function authenticated_user_can_select_language_and_update_user_language_in_database()
    {
        $randomLocale = $this->getRandomLocale();
        $user = User::factory()->create(['language' => '']);

        Livewire::actingAs($user)
            ->test(LanguageSwitcher::class)
            ->set('selectedLanguage', $randomLocale)
            ->assertSessionHas('locale', $randomLocale);

        $this->assertEquals($randomLocale, $user->refresh()->language);
    }

    /**
     * Test: Set locale from authenticated user's language preference.
     *
     * Steps:
     *  1. Create a user with the 'hu' language attribute.
     *  2. Authenticate as the user.
     *  3. Render the LanguageSwitcher component.
     *  4. Set the selected language in the component to the user's language.
     *  5. Assert that the application locale matches the user's language attribute.
     */
    /** @test */
    public function it_sets_locale_from_authenticated_user()
    {
        $user = User::factory()->create(['language' => 'hu']);
        $this->actingAs($user);
        $component = Livewire::test(LanguageSwitcher::class);
        $component->set('selectedLanguage', $user->language);
        $this->assertEquals(app()->getLocale(), $user->language);
    }

    /**
     * Helper method to get a random locale from available locales.
     *
     * @return string The randomly selected locale.
     */
    private function getRandomLocale()
    {
        $availableLocales = array_keys(Config::get('app.locales'));

        return $availableLocales[array_rand($availableLocales)];
    }
}
