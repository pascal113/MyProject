<?php

use App\Models\Page;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ShopAllProductsPageTicket808 extends Seeder
{
    use FileCopyTrait;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/merchandise.jpg',
            'quote_image' => 'Pages/images/quote-avatar-mary.png',
            'paw_packs_image' => 'Pages/images/wash-clubs/ticket-books.jpg',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        $parentPage = Page::where('slug', 'shop')->firstOrFail();

        // Update existing Pages to use unified template
        Page::where('parent_page_id', $parentPage->id)
            ->where(function ($query) {
                $query->where('template', 'shop.branded-merchandise')
                    ->orWhere('template', 'shop.paw-packs-ticket-books');
            })
            ->update([ 'template' => 'shop.products' ]);

        // Create new Page
        Page::create([
            'parent_page_id' => $parentPage->id,
            'slug' => 'all-products',
            'category' => 'main',
            'template' => 'shop.products',
            'title' => 'All Products',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => 'All Products',
                    'image' => $destImages['hero'],
                ],
                'intro' => (object)[
                    'heading' => 'All Products',
                    'paragraphs' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.',
                ],

                'quote' => (object)[
                    'image' => $destImages['quote_image'],
                    'quote' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua!',
                    'attribution' => 'Mary, Seattle',
                ],
                'products' => (object)[
                    'heading' => 'All Products',
                    'items' => Product::all()->map(function ($product) {
                        return [ 'id' => $product->id ];
                    }),
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
