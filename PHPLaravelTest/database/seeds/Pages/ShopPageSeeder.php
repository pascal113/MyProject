<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class ShopPageSeeder extends Seeder
{
    use FileCopyTrait;
    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/leadership.jpg',
            'quote_image' => 'Pages/images/careers/quote-avatar-jimmy.png',
            'paw_packs_image' => 'Pages/images/wash-clubs/ticket-books.jpg',
            'branded_image' => 'Pages/images/wash-clubs/branded-merchandise.jpg',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        Page::create([
            'slug' => 'shop',
            'category' => 'main',
            'template' => 'shop.index',
            'title' => 'Shop',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => 'Love Brown Bear?',
                    'image' => $destImages['hero'],
                ],
                'intro' => (object)[
                    'heading' => 'Welcome to Our Shop!',
                    'paragraphs' => 'Show off your Brown Bear pride with our original merchandise, Paw Packs and Ticket Books. From bears to bubbles, we have it all.',
                ],
                'allProducts' => (object)[
                    'image' => $destImages['paw_packs_image'],
                    'heading' => 'All Products',
                    'paragraphs' => 'Check out these Paw Packs and Ticket Books branded with different designs. These make great gifts for family, friends or co-workers and offer different levels of washes.',
                    'button' => (object)[
                        'route' => 'shop.all-products',
                        'text' => 'Browse All Products',
                    ],
                ],
                'unlimitedWashClubMemberships' => (object)[
                    'image' => $destImages['paw_packs_image'],
                    'heading' => 'Unlimited Wash Club Memberships',
                    'paragraphs' => 'Check out these Unlimited Wash Club Memberships. Save money and keep your car spotless!',
                    'button' => (object)[
                        'route' => 'shop.wash-club-memberships',
                        'text' => 'Browse Unlimited Wash Club Memberships',
                    ],
                ],
                'pawPacksAndTicketBooks' => (object)[
                    'image' => $destImages['paw_packs_image'],
                    'heading' => 'Wash Cards & Ticket Books',
                    'paragraphs' => 'Check out these Paw Packs and Ticket Books branded with different designs. These make great gifts for family, friends or co-workers and offer different levels of washes.',
                    'button' => (object)[
                        'route' => 'shop.wash-cards-ticket-books',
                        'text' => 'Browse Wash Cards & Ticket Books',
                    ],
                ],
                'brandedMerchandise' => (object)[
                    'image' => $destImages['branded_image'],
                    'heading' => 'Branded Merchandise',
                    'paragraphs' => 'Water bottles, bubbles and teddy bears, oh my! Find your perfect Brown Bear Merchandise for your own car or a great gift!',
                    'button' => (object)[
                        'route' => 'shop.branded-merchandise',
                        'text' => 'Browse Branded Merchandise',
                    ],
                ],
                'quote' => (object)[
                    'image' => $destImages['quote_image'],
                    'quote' => 'Brown Bear is an awesome Seattle company and an iconic brand. I love showing off by Brown Bear pride with fun merchandise.',
                    'attribution' => 'Jimmy, Seattle',
                ],
            ],
        ]);
    }
}
