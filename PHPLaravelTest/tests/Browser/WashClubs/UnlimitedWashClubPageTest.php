<?php

namespace Tests\Browser\WashClubs;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UnlimitedWashClubPageTest extends DuskTestCase
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/wash-clubs/unlimited-wash-club';
    }

    /**
     * Visual regression test for page.
     *
     * @return void
     */
    public function testUnlimitedWashClubPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->url())
                ->assertPathIs($this->url())
                ->assertTitle('Brown Bear Car Wash')
                ->snapshot();
        });
    }
}
