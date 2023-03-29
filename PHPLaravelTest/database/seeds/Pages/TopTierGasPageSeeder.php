<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class TopTierGasPageSeeder extends Seeder
{
    use FileCopyTrait;
    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/top-tier-gas.jpg',
            'top_tier_1' => 'Pages/images/top-tier-gas/top-tier-gas001.jpg',
            'top_tier_2' => 'Pages/images/top-tier-gas/top-tier-gas002.jpg',
            'brands_1' => 'Pages/images/top-tier-gas/gas-chevron.png',
            'brands_2' => 'Pages/images/top-tier-gas/gas-texaco.png',
            'brands_3' => 'Pages/images/top-tier-gas/gas-76.png',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        $parentPage = Page::where('slug', 'about-our-washes')->firstOrFail();

        Page::create([
            'parent_page_id' => $parentPage->id,
            'slug' => 'top-tier-gas',
            'category' => 'main',
            'template' => 'about-our-washes.top-tier-gas',
            'title' => 'Top-Tier Gas',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => 'The best gas to keep you going!',
                    'image' => $destImages['hero'],
                ],
                'intro' => (object)[
                    'heading' => 'Top-Tier Gas: Clean & Reliable',
                    'paragraphs' => 'Our outlets sell gasoline brands including Chevron, Texaco, and ConocoPhillips 76. Top Tier gasolines contain high quality additives that protect critical engine parts.',
                ],
                'contentBlocks' => [
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::IMAGES_AND_TEXT,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_BLUE,
                        'icon' => 'gas',
                        'images' => [
                            $destImages['top_tier_1'],
                            $destImages['top_tier_2'],
                        ],
                        'heading' => 'Top-Tier Gas',
                        'wysiwyg' => "TOP TIER™ is recognized as a premier fuel performance specification developed and enforced by leading automotive and heavy duty equipment manufacturers. The intention of TOP TIER™ is to create a winning situation for retailers, auto manufacturers, and drivers.\n\nVehicle and equipment manufacturer sponsors have a specification by which they can drive market fuel improvemenxts to meet the ever changing demands of engine technology when regulations or specifications may not go far enough.",
                        'button' => (object)[
                            'route' => 'locations.index',
                            'text' => 'Find Top-Tier Gas',
                        ],
                    ],
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::ICON_AND_PARAGRAPH,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_WHITE,
                        'heading' => 'What is top-tier gas?',
                        'icon' => 'mag-glass',
                        'wysiwyg' => "TOP TIER™ is recognized as a premier fuel performance specification developed and enforced by leading automotive and heavy duty equipment manufacturers. The intention of TOP TIER™ is to create a winning situation for retailers, auto manufacturers, and drivers.\n\nVehicle and equipment manufacturer sponsors have a specification by which they can drive market fuel improvements to meet the ever changing demands of engine technology when regulations or specifications may not go far enough.",
                        'button' => (object)[
                            'route' => 'http://www.toptiergas.com/',
                            'text' => 'About Top-Tier Gas',
                            'openInNewTab' => true,
                        ],
                    ],
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::CARD,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_BLUE,
                        'heading' => 'Our Gas Brands',
                        'items' => [
                            (object)[
                                'heading' => 'Chevron',
                                'image' => $destImages['brands_1'],
                                'paragraphs' => "The first U.S. gasoline marketer to have its gasoline approved as meeting new performance criteria for Top Tier Gasoline. Techron® Quality Gas, diesel fuels, automotive transmission fluids and our range of motor oils have been developed to meet your needs.",
                            ],
                            (object)[
                                'heading' => 'Texaco',
                                'image' => $destImages['brands_2'],
                                'paragraphs' => "Texaco's Techron® additive cleans vital engine parts to help your car reach its performance potential. For over 100 years, Texaco has provided motorists with gasoline that delivers superior power, quality and performance.",
                            ],
                            (object)[
                                'heading' => '76®',
                                'image' => $destImages['brands_3'],
                                'paragraphs' => "Phillips 66's 76® PROclean™ gasoline contains higher levels of detergent additives that help prevent deposits and help ensure peak engine performance.",
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
