<?php

namespace Tests\Browser\CommercialPrograms;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ForHireUnlimitedWashClubPageTest extends DuskTestCase
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/wash-clubs/for-hire-unlimited-wash-club';
    }

    /**
     * Visual regression test for page.
     *
     * @return void
     */
    public function testForHireUnlimitedWashClubPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->url())
                ->assertPathIs($this->url())
                ->assertTitle('Brown Bear Car Wash')
                ->snapshot();
        });
    }
}
