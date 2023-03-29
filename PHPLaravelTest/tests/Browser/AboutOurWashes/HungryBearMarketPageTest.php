<?php

namespace Tests\Browser\AboutOurWashes;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class HungryBearMarketPageTest extends DuskTestCase
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/about-our-washes/hungry-bear-market';
    }

    /**
     * Visual regression test for page.
     *
     * @return void
     */
    public function testHungryBearMarketPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->url())
                ->assertPathIs($this->url())
                ->assertTitle('Brown Bear Car Wash')
                ->snapshot();
        });
    }
}
