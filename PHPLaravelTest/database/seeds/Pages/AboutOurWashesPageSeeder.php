<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class AboutOurWashesPageSeeder extends Seeder
{
    use FileCopyTrait;
    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/about-our-washes.jpg',
            'tunnelWash' => 'Pages/images/about-our-wash/img-tunnel.jpg',
            'selfServeWash' => 'Pages/images/about-our-wash/img-self-serve.jpg',
            'touchlessWash' => 'Pages/images/about-our-wash/img-touchless.jpg',
            'topTierGas' => 'Pages/images/about-our-wash/img-gas.jpg',
            'hungryBearMarket' => 'Pages/images/about-our-wash/img-market.jpg',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        Page::create([
            'slug' => 'about-our-washes',
            'category' => 'main',
            'template' => 'about-our-washes.index',
            'title' => 'About Our Washes',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => 'Which car wash is right for you?',
                    'image' => $destImages['hero'],
                ],
                'intro' => (object)[
                    'heading' => 'About Our Washes',
                    'paragraphs' => 'With over 60 convenient Puget Sound locations Brown Bear Car Wash is here to keep your car looking great.',
                ],
                'tunnelWash' => (object)[
                    'heading' => 'Tunnel Wash',
                    'icon' => 'tunnel',
                    'image' => $destImages['tunnelWash'],
                    'paragraphs' => 'Tunnel Washes are our most popular car wash format. The easy drive-thru design allows us to quickly clean your car with express exterior cleaning...',
                    'button' => (object)[
                        'route' => 'about-our-washes.tunnel-car-wash',
                        'text' => 'More About Tunnel Washes',
                    ],
                ],
                'selfServeWash' => (object)[
                    'heading' => 'Self-Serve Wash',
                    'icon' => 'self',
                    'image' => $destImages['selfServeWash'],
                    'paragraphs' => 'Self-Serve Washes allow you to clean your car exterior yourself with powerful and well maintained equipment...',
                    'button' => (object)[
                        'route' => 'about-our-washes.self-serve-car-wash',
                        'text' => 'More About Self-Serve Washes',
                    ],
                ],
                'touchlessWash' => (object)[
                    'heading' => 'Touchless Wash',
                    'icon' => 'touchless',
                    'image' => $destImages['touchlessWash'],
                    'paragraphs' => 'For large vehicles, or those who prefer a touch-free washing experience, the touchless wash offers a superior experience...',
                    'button' => (object)[
                        'route' => 'about-our-washes.touchless-car-wash',
                        'text' => 'More About Touchless Washes',
                    ],
                ],
                'topTierGas' => (object)[
                    'heading' => 'Top-Tier Gas',
                    'icon' => 'gas',
                    'image' => $destImages['topTierGas'],
                    'paragraphs' => 'Many of our outlets also sell premium "top tier" gasoline brands including Chevron, Texaco, and ConocoPhillips 76. Top Tier gasolines contain high quality additives that protect critical engine parts.',
                    'button' => (object)[
                        'route' => 'about-our-washes.top-tier-gas',
                        'text' => 'More About Top-Tier Gas',
                    ],
                ],
                'hungryBearMarket' => (object)[
                    'heading' => 'Hungry Bear Market',
                    'icon' => 'market',
                    'image' => $destImages['hungryBearMarket'],
                    'paragraphs' => 'Hungry Bear Market™ offers fast, friendly, and clean stores that go beyond your average convenience stores.',
                    'button' => (object)[
                        'route' => 'about-our-washes.hungry-bear-market',
                        'text' => 'More About Hungry Bear Market',
                    ],
                ],
            ],
        ]);
    }
}
