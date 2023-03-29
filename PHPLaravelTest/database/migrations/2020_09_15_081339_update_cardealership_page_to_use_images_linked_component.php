<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

class UpdateCardealershipPageToUseImagesLinkedComponent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $page = Page::where('slug', 'car-dealership-program')->first();

        if ($page) {
            $carDealershipLogos = $page->content->participatingDealerships->dealershipLogos;

            $images_linked = [];
            foreach ($carDealershipLogos as $logo) {
                $images_linked[] = (object)[
                    'url' => (object)[
                        'value' => null,
                    ],
                    'image' => $logo,
                ];
            }

            $content = $page->content;
            $content->participatingDealerships->dealershipLogos = (object)[ 'items' => $images_linked ];
            $page->update([ 'content' => $content ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $page = Page::where('slug', 'car-dealership-program')->first();

        if ($page) {
            $carDealershipLogos = $page->content->participatingDealerships->dealershipLogos;

            if (isset($carDealershipLogos->items)) {
                $images = [];
                foreach ($carDealershipLogos->items as $logo) {
                    $images[] = $logo->image;
                }

                $content = $page->content;
                $content->participatingDealerships->dealershipLogos = $images;
                $page->update([ 'content' => $content ]);
            }
        }
    }
}
