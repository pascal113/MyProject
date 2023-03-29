<?php

declare(strict_types=1);

use App\Models\Page;
use Illuminate\Database\Seeder;

class HomePageSeeder extends Seeder
{
    use FileCopyTrait;

    public function run()
    {
        $srcImages = [
            'hero_desktop_1' => 'Pages/images/home/slide-home001.jpg',
            'hero_mobile_1' => 'Pages/images/home/slide-home001-mobile.jpg',
            'hero_desktop_2' => 'Pages/images/home/slide-home002.jpg',
            'hero_mobile_2' => 'Pages/images/home/slide-home002-mobile.jpg',
            'hero_desktop_3' => 'Pages/images/home/slide-home003.jpg',
            'hero_mobile_3' => 'Pages/images/home/slide-home003-mobile.jpg',
            'card_1' => 'Pages/images/home/unlimited.jpg',
            'card_2' => 'Pages/images/home/merch.jpg',
            'card_3' => 'Pages/images/home/car.jpg',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        Page::create([
            'id' => 1,
            'slug' => '/',
            'category' => 'main',
            'template' => 'home',
            'title' => 'Home',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'carousel' => (object)[
                    'items' => [
                        (object)[
                            'desktopBackgroundImage' => $destImages['hero_desktop_1'],
                            'mobileBackgroundImage' => $destImages['hero_mobile_1'],
                            'heading' => 'A fixture in the Puget Sound region since 1957.',
                            'button' => (object)[
                                'text' => 'Learn About Our History',
                                'route' => 'our-company.our-history',
                            ],
                        ],
                        (object)[
                            'desktopBackgroundImage' => $destImages['hero_desktop_2'],
                            'mobileBackgroundImage' => $destImages['hero_mobile_2'],
                            'heading' => 'The earth-friendly way to clean your car.',
                            'image' => '/images/img-washGreen.svg',
                            'button' => (object)[
                                'text' => 'Learn About Wash Green',
                                'route' => 'community-commitment.wash-green',
                            ],
                        ],
                        (object)[
                            'desktopBackgroundImage' => $destImages['hero_desktop_3'],
                            'mobileBackgroundImage' => $destImages['hero_mobile_3'],
                            'heading' => 'Many locations in Puget Sound, one that’s right for you.',
                            'button' => (object)[
                                'text' => 'Find a Wash',
                                'route' => 'locations.index',
                            ],
                        ],
                    ],
                ],
                'welcome' => (object)[
                    'heading' => 'Welcome to Brown Bear Car Wash!',
                    'paragraphs' => 'With over 60 convenient Puget Sound locations Brown Bear Car Wash is here to keep your car looking great.',
                    'button' => (object)[
                        'text' => 'Find a Wash',
                        'route' => 'locations.index',
                    ],
                ],
                'cards' => (object)[
                    'heading' => 'Brown Bear is your car wash!',
                    'items' => [
                        (object)[
                            'heading' => 'Always be clean!',
                            'image' => $destImages['card_1'],
                            'description' => 'Join our Unlimited Wash Club and keep your car looking great. Enjoy unlimited washes at any tunnel location for one low price.',
                            'button' => (object)[
                                'route' => 'wash-clubs.unlimited-wash-club',
                                'text' => 'Unlimited Car Wash',
                            ],
                        ],
                        (object)[
                            'heading' => 'Love Brown Bear?',
                            'image' => $destImages['card_2'],
                            'description' => 'Show off your Brown Bear pride with our original merchandise. From bears to bubbles, we have it all.',
                            'button' => (object)[
                                'route' => 'shop.branded-merchandise',
                                'text' => 'View Merchandise',
                            ],
                        ],
                        (object)[
                            'heading' => 'Did you know...',
                            'image' => $destImages['card_3'],
                            'description' => 'Brown Bear Car Wash has been operating since 1957. In fact, this iconic brand was the original workplace of some of the area’s great leaders.',
                            'button' => (object)[
                                'route' => 'our-company.our-history',
                                'text' => 'Our History',
                            ],
                        ],
                    ],
                ],
                'askAQuestion' => (object)[
                    'heading' => 'Questions?',
                    'paragraphs' => 'If you have questions about Brown Bear, feel free to reach out, and a team member will get in touch. We appreciate your interest in our company.',
                    'button' => (object)[
                        'text' => 'Ask a Question',
                        'route' => 'support.contact-us',
                    ],
                ],
            ],
        ]);
    }
}
