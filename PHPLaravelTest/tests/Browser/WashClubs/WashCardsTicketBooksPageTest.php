<?php

namespace Tests\Browser\WashClubs;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class WashCardsTicketBooksPageTest extends DuskTestCase
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/shop/wash-cards-ticket-books';
    }

    /**
     * Visual regression test for page.
     *
     * @return void
     */
    public function testWashCardsTicketBooksPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->url())
                ->assertPathIs($this->url())
                ->assertTitle('Brown Bear Car Wash')
                ->snapshot();
        });
    }
}
