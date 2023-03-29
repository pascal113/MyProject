<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class LocationsPageSeeder extends Seeder
{
    use FileCopyTrait;
    public function run()
    {
        Page::create([
            'slug' => 'locations',
            'category' => 'main',
            'template' => 'locations.index',
            'title' => 'Locations',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,
            'content' => null,
        ]);
    }
}
