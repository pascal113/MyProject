<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class LocationsPageUpdateSeeder extends Seeder
{
    use FileCopyTrait;
    public function run()
    {
        $locationsPage = Page::where('slug', 'locations')->first();

        $locationsPage->content = (object)[
            'hero' => (object)[
                'heading' => 'Many washes in Puget Sound, one that’s right for you.',
            ],
            'intro' => (object)[
                'heading' => 'Find a Wash',
            ],
        ];
        $locationsPage->save();
    }
}
