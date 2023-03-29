<?php

use App\Models\Page;

class CharitableDonationsPageUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parentPage = Page::where('slug', 'community-commitment')->firstOrFail();
        Page::where([
            'slug' => 'charitable-donations',
            'parent_page_id' => $parentPage->id,
        ])->update([
            'slug' => 'giving-back',
            'title' => 'Giving Back',
        ]);
    }
}
