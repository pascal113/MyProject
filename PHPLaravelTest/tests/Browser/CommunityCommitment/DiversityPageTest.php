<?php

namespace Tests\Browser\CommunityCommitment;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DiversityPageTest extends DuskTestCase
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/community-commitment/diversity-inclusion';
    }

    /**
     * Visual regression test for page.
     *
     * @return void
     */
    public function testDiversityPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->url())
                ->assertPathIs($this->url())
                ->assertTitle('Brown Bear Car Wash')
                ->snapshot();
        });
    }
}
