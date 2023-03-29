<?php

use App\Models\Page;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ShopWashCardsAndTicketBooksPageTicket841 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Rename Paw Packs -> Wash Cards ProductCategory
        ProductCategory::where([
            'slug' => 'paw-packs-ticket-books',
        ])->update([
            'slug' => 'wash-cards-ticket-books',
            'name' => 'Wash Cards & Ticket Books',
        ]);

        // Rename Paw Packs -> Wash Cards Page
        $shopPage = Page::where('slug', 'shop')->firstOrFail();
        Page::where([
            'parent_page_id' => $shopPage->id,
            'slug' => 'paw-packs-ticket-books',
        ])->update([
            'slug' => 'wash-cards-ticket-books',
            'title' => 'Wash Cards & Ticket Books',
        ]);

        // Update CMS links to old paw-packs URL
        $pages = Page::where('content', 'like', '%"route": "shop.paw-packs-ticket-books"%')->get();
        $searchValue = '/"route":([ ]*)"shop.paw-packs-ticket-books"/';
        $replaceValue = '"route":$1"shop.wash-cards-ticket-books"';
        foreach ($pages as $page) {
            $pageContent = $page->content;
            $updatedContent = preg_replace($searchValue, $replaceValue, json_encode($pageContent));
            $page->content = json_decode($updatedContent);
            $page->save();
        }
    }
}
