<?php

namespace Tests\Browser\Locations;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LocationsPageTest extends DuskTestCase
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/locations';
    }

    /**
     * Visual regression test for page.
     *
     * @return void
     */
    public function testLocationsIndexPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->url())
                ->assertPathIs($this->url())
                ->assertTitle('Brown Bear Car Wash')
                ->snapshot();
        });
    }
}
