<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;

class EssentialPagesCategoryUpdateSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $pagesTemplate = [
            'locations.index',
            'about-our-washes.index',
            'wash-clubs.index',
            'commercial-programs.index',
            'shop.index',
            'community-commitment.index',
            'our-company.index',
            'support.index',
        ];

        \App\Models\Page::whereIn('template', $pagesTemplate)->update(['category' => 'essential']);
    }
}
