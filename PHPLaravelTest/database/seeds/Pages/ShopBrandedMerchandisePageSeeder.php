<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class ShopBrandedMerchandisePageSeeder extends Seeder
{
    use FileCopyTrait;
    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/merchandise.jpg',
            'quote_image' => 'Pages/images/quote-avatar-mary.png',
            'paw_packs_image' => 'Pages/images/wash-clubs/ticket-books.jpg',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        $parentPage = Page::where('slug', 'shop')->firstOrFail();

        Page::create([
            'parent_page_id' => $parentPage->id,
            'slug' => 'branded-merchandise',
            'category' => 'main',
            'template' => 'shop.branded-merchandise',
            'title' => 'Branded Merchandise',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => 'Bring home the best of Brown Bear!',
                    'image' => $destImages['hero'],
                ],
                'intro' => (object)[
                    'heading' => 'Branded Merchandise',
                    'paragraphs' => 'Our iconic branded merchandise is always a hit with adults and kids alike. Keep your car looking (and smelling) great with a little love from your favorite brown bear!',
                ],

                'quote' => (object)[
                    'image' => $destImages['quote_image'],
                    'quote' => 'I love showing off my Brown Bear pride with my swag! I get compliments all the time. It is such a cool and iconic brand!',
                    'attribution' => 'Mary, Seattle',
                ],
                'products' => (object)[
                    'heading' => 'Show your Brown Bear pride!',
                    'items' => [
                        ['id' => 1],
                        ['id' => 2],
                        ['id' => 3],
                        ['id' => 4],
                        ['id' => 5],
                        ['id' => 6],
                    ],
                ],
                'relatedContent' => (object)[
                    'image' => $destImages['paw_packs_image'],
                    'heading' => 'Paw Packs & Ticket Books',
                    'paragraphs' => 'Check out these Paw Packs and Ticket Books branded with different designs. These make great gifts for family, friends or co-workers and offer different levels of washes.',
                    'button' => (object)[
                        'route' => 'shop.paw-packs-ticket-books',
                        'text' => 'Browse Paw Packs & Ticket Books',
                    ],
                ],
            ],
        ]);
    }
}
