<?php

declare(strict_types=1);

namespace Tests\Browser\Locations;

use App\Models\Location;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LocationPageTest extends DuskTestCase
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        $location = $this->location();

        return '/locations/'.$location->id;
    }

    /**
     * Get the Location to be used for tests.
     *
     * @return string
     */
    public function location()
    {
        return Location::first();
    }

    /**
     * Visual regression test for page.
     *
     * @return void
     */
    public function testLocationsDetailPage()
    {
        $this->browse(function (Browser $browser) {
            $location = $this->location();

            $browser->visit($this->url())
                ->assertPathIs($this->url())
                ->assertTitle($location->meta_title ?? 'Brown Bear Car Wash')
                ->snapshot();
        });
    }

    /**
     * Visual regression test for send feedback popup
     *
     * @return void
     */
    public function testSendFeedbackModal()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->url())
                ->assertPathIs($this->url())
                ->click('@send-feedback')
                ->pause(500)
                ->snapshot('Send Feedback popup over Location Detail');
        });
    }
}
