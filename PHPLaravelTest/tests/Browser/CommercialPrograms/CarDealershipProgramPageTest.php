<?php

namespace Tests\Browser\CommercialPrograms;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CarDealershipProgramPageTest extends DuskTestCase
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/commercial-programs/car-dealership-program';
    }

    /**
     * Visual regression test for page.
     *
     * @return void
     */
    public function testCarDealershipProgramPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->url())
                ->assertPathIs($this->url())
                ->assertTitle('Brown Bear Car Wash')
                ->snapshot();
        });
    }
}
