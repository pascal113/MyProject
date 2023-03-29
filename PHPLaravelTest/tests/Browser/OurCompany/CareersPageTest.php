<?php

namespace Tests\Browser\OurCompany;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CareersPageTest extends DuskTestCase
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/our-company/careers';
    }

    /**
     * Visual regression test for page.
     *
     * @return void
     */
    public function testOurCompanyCareersPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->url())
                ->assertPathIs($this->url())
                ->assertTitle('Brown Bear Car Wash')
                ->snapshot();
        });
    }
}
