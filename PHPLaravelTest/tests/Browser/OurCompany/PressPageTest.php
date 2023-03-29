<?php

namespace Tests\Browser\OurCompany;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PressPageTest extends DuskTestCase
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/our-company/press-center';
    }

    /**
     * Visual regression test for page.
     *
     * @return void
     */
    public function testOurCompanyPressPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->url())
                ->assertPathIs($this->url())
                ->assertTitle('Brown Bear Car Wash')
                ->snapshot();
        });
    }
}
