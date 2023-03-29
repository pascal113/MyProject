<?php

namespace Tests\Browser\Support;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ContactUsPageTest extends DuskTestCase
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/support/contact-us';
    }

    /**
     * Visual regression test for page.
     *
     * @return void
     */
    public function testContactUsPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->url())
                ->assertPathIs($this->url())
                ->assertTitle('Brown Bear Car Wash')
                ->snapshot();
        });
    }
}
