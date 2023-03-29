<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class HungryBearMarketPageSeeder extends Seeder
{
    use FileCopyTrait;
    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/market.jpg',
            'market_1' => 'Pages/images/market/market001.jpg',
            'market_2' => 'Pages/images/market/market002.jpg',
            'quote' => 'Pages/images/market/quote-avatar-christie.png',
            'comeAndSeeUs_1' => 'Pages/images/img-bear-cooking.svg',
            'comeAndSeeUs_2' => 'Pages/images/logo-market.png',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        $parentPage = Page::where('slug', 'about-our-washes')->firstOrFail();

        Page::create([
            'parent_page_id' => $parentPage->id,
            'slug' => 'hungry-bear-market',
            'category' => 'main',
            'template' => 'about-our-washes.hungry-bear-market',
            'title' => 'Hungry Bear Market',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => 'Everything you need on the go!',
                    'image' => $destImages['hero'],
                ],
                'intro' => (object)[
                    'heading' => 'Hungry Bear Market: Fast & Convenient',
                    'paragraphs' => 'Hungry Bear Market™ offers fast, friendly, and clean stores that go beyond your average convenience stores.',
                ],
                'contentBlocks' => [
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::IMAGES_AND_TEXT,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_BLUE,
                        'icon' => 'market',
                        'images' => json_encode([
                            $destImages['market_1'],
                            $destImages['market_2'],
                        ]),
                        'heading' => 'Hungry Bear Market',
                        'wysiwyg' => "Hungry Bear Market™ offers fast, friendly, and clean stores that go beyond your average convenience stores.\n\nHungry Bear Market offers a wide variety of snacks, beverages and everything in between to meet your busy life, including our very own Grizzly Grind Coffee. All Hungry Bear Markets offer quality Top Tier Gasoline too, just another way to serve you.",
                        'button' => (object)[
                            'route' => 'locations.index',
                            'text' => 'Find a Hungry Bear Market',
                        ],
                    ],
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::QUOTE,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_WHITE,
                        'attribution' => 'Christie, Kirkland',
                        'image' => $destImages['quote'],
                        'quote' => 'Hungry Bear Market has everything I need to keep my kids and husband happy on our stops to Brown Bear Car Wash.',
                    ],
                ],
                'comeAndSeeUs' => (object)[
                    'heading' => 'Come and see us!',
                    'image' => $destImages['comeAndSeeUs_1'],
                    'logo' => $destImages['comeAndSeeUs_2'],
                ],
            ],
        ]);
    }
}
