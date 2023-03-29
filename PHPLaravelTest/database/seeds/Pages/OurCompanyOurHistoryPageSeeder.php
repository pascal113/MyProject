<?php

declare(strict_types=1);

use App\Models\Page;
use Illuminate\Database\Seeder;

class OurCompanyOurHistoryPageSeeder extends Seeder
{
    use FileCopyTrait;
    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/history.jpg',
            'main_top_image1' => 'Pages/images/our-company/history/history001.jpg',
            'main_top_image2' => 'Pages/images/our-company/history/history002.jpg',
            'main_middle_image1' => 'Pages/images/our-company/history/history003.jpg',
            'main_bottom_image1' => 'Pages/images/our-company/history/history005.jpg',
            'main_bottom_image2' => 'Pages/images/our-company/history/history004.jpg',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        $parentPage = Page::where('slug', 'our-company')->firstOrFail();

        Page::create([
            'parent_page_id' => $parentPage->id,
            'slug' => 'our-history',
            'category' => 'main',
            'template' => 'our-company.our-history',
            'title' => 'Our History',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => 'In the beginning...',
                    'image' => $destImages['hero'],
                ],
                'intro' => (object)[
                    'heading' => 'Our History',
                    'paragraphs' => "Brown Bear Car Wash began in Seattle in 1957. Victor Odermat opened the first Brown Bear Car Wash on 15th Avenue West in Seattle's Interbay neighborhood, and that location is still in operation today.",
                ],
                'main' => (object)[
                    'heading' => 'Back then, things weren’t so different.',
                    'topImages' => [
                        $destImages['main_top_image1'],
                        $destImages['main_top_image2'],
                    ],
                    'topWysiwyg' => "## How it all started...\n\n"
                        ."Brown Bear Car Wash began in Seattle in 1957. Victor Odermat opened the first Brown Bear Car Wash on 15th Avenue West in Seattle's Interbay neighborhood, and that location is still in operation today.\n\n"
                        ."Our founder was working in Seattle and Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam rutrum luctus orci, a convallis orci pharetra vel. In accumsan sem elit. Vestibulum elementum tincidunt velit ac consequat. Vestibulum rhoncus, urna et hendrerit volutpat, dui lorem consequat nibh, in tempor velit diam id elit. Phasellus non dictum metus. In bibendum consectetur tellus at sodales. Aenean fringilla ipsum blandit, tempus nisi non, rutrum lacus.\n\n"
                        ."Our founder was working in Seattle and Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam rutrum luctus orci, a convallis orci pharetra vel. In accumsan sem elit. Vestibulum elementum tincidunt velit ac consequat. Vestibulum rhoncus, urna et hendrerit volutpat, dui lorem consequat nibh, in tempor velit diam id elit. Phasellus non dictum metus. In bibendum consectetur tellus at sodales. Aenean fringilla ipsum blandit, tempus nisi non, rutrum lacus.",
                    'middleImages' => [
                        $destImages['main_middle_image1'],
                    ],
                    'bottomWysiwyg' => "## Back Then...\n\n"
                        ."A car wash cost fifty cents.\n\n"
                        ."Soda was ten cents.\n\n"
                        ."And XYZ was the top hit on the radio\n\n"
                        ."## A Proud Fixture of the Northwest\n\n"
                        ."For three generations we have employed and hundred of people in the Seattle area Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam rutrum luctus orci, a convallis orci pharetra vel. In accumsan sem elit. Vestibulum elementum tincidunt velit ac consequat. Vestibulum rhoncus, urna et hendrerit volutpat, dui lorem consequat nibh, in tempor velit diam id elit. Phasellus non dictum metus. In bibendum consectetur tellus at sodales. Aenean fringilla ipsum blandit, tempus nisi non, rutrum lacus.\n\n"
                        ."Our founder was working in Seattle and Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam rutrum luctus orci, a convallis orci pharetra vel. In accumsan sem elit. Vestibulum elementum tincidunt velit ac consequat. Vestibulum rhoncus, urna et hendrerit volutpat, dui lorem consequat nibh, in tempor velit diam id elit. Phasellus non dictum metus. In bibendum consectetur tellus at sodales. Aenean fringilla ipsum blandit, tempus nisi non, rutrum lacus.",
                    'bottomImages' => [
                        $destImages['main_bottom_image1'],
                        $destImages['main_bottom_image2'],
                    ],
                ],
            ],
        ]);
    }
}
