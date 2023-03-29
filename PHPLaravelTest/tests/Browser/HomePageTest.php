<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class HomePageTest extends DuskTestCase
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/';
    }

    /**
     * Visual regression test for page.
     *
     * @return void
     */
    public function testHomePage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->url())
                ->assertPathIs($this->url())
                ->assertTitle('Brown Bear Car Wash')
                ->snapshot();
        });
    }

    /**
     * Visual regression test for main nav
     *
     * @return void
     */
    public function testMainNav()
    {
        Browser::macro('toggleAllMainNavSubsections', function () {
            $this->script('$(".js-nav-trigger").click();');

            return $this;
        });

        $this->browse(function (Browser $browser) {
            $browser->visit($this->url())
                ->assertPathIs($this->url())
                ->click('@main-nav-toggle')
                ->toggleAllMainNavSubsections()
                ->snapshot('Main Nav over Home');
        });
    }
}
