<?php

namespace Tests\Browser\CommercialPrograms;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class FleetWashProgramPageTest extends DuskTestCase
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/commercial-programs/fleet-wash-program';
    }

    /**
     * Visual regression test for page.
     *
     * @return void
     */
    public function testFleetWashProgramPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->url())
                ->assertPathIs($this->url())
                ->assertTitle('Brown Bear Car Wash')
                ->snapshot();
        });
    }
}
