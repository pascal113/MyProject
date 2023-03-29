<?php

namespace Tests\Browser\AboutOurWashes;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AboutOurWashesPageTest extends DuskTestCase
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/about-our-washes';
    }

    /**
     * Visual regression test for page.
     *
     * @return void
     */
    public function testAboutOurWashesIndexPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->url())
                ->assertPathIs($this->url())
                ->assertTitle('Brown Bear Car Wash')
                ->snapshot();
        });
    }
}
