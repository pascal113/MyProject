<?php

namespace Tests\Browser\CommunityCommitment;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CharitableDonationsPageTest extends DuskTestCase
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/community-commitment/giving-back';
    }

    /**
     * Visual regression test for page.
     *
     * @return void
     */
    public function testCharitableDonationsPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->url())
                ->assertPathIs($this->url())
                ->assertTitle('Brown Bear Car Wash')
                ->snapshot();
        });
    }
}
