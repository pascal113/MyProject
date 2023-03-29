<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class ShopPawPacksTicketBooksPageSeeder extends Seeder
{
    use FileCopyTrait;
    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/tunnel-wash.jpg',
            'quote_image' => 'Pages/images/quote-avatar-mary.png',
            'branded_image' => 'Pages/images/wash-clubs/branded-merchandise.jpg',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        $parentPage = Page::where('slug', 'shop')->firstOrFail();

        Page::create([
            'parent_page_id' => $parentPage->id,
            'slug' => 'paw-packs-ticket-books',
            'category' => 'main',
            'template' => 'shop.paw-packs-ticket-books',
            'title' => 'Paw Packs & Ticket Books',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => 'Paw Packs & Ticket Books good for tunnel washes everywhere!',
                    'image' => $destImages['hero'],
                ],
                'intro' => (object)[
                    'heading' => 'Paw Packs & Ticket Books',
                    'paragraphs' => 'Check out these Paw Packs and Ticket Books branded with different designs. These make great gifts for family, friends or co-workers and offer different levels of washes.',
                ],

                'quote' => (object)[
                    'image' => $destImages['quote_image'],
                    'quote' => 'I love the Brown Bear Tunnel Washes! They are super quick, and very effective at cleaning my car. I can get my vehicle looking great before business meetings. It is so convenient. Wow.',
                    'attribution' => 'Mary, Seattle',
                ],
                'products' => (object)[
                    'heading' => 'Buy wash packs and save',
                    'items' => [
                        (object)['id' => 7],
                        (object)['id' => 8],
                        (object)['id' => 9],
                        (object)['id' => 10],
                        (object)['id' => 11],
                        (object)['id' => 12],
                        (object)['id' => 13],
                    ],
                ],
                'relatedContent' => (object)[
                    'image' => $destImages['branded_image'],
                    'heading' => 'Branded Merchandise',
                    'paragraphs' => 'Water bottles, bubbles and teddy bears, oh my! Find your perfect Brown Bear Merchandise for your own car or a great gift!',
                    'button' => (object)[
                        'route' => 'shop.branded-merchandise',
                        'text' => 'Browse Branded Merchandise',
                    ],
                ],
            ],
        ]);
    }
}
