<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class PagesUpdateSeeder extends Seeder
{
    use FileCopyTrait;

    public function run()
    {
        $searchValue = '?regarding=Programs Inquiry&program=Unlimited Wash Club&show=email';
        $replaceValue = '?regarding=Unlimited Wash Club&show=email';
        $pages = Page::where('content', 'like', '%'.$searchValue.'%')->get();

        foreach ($pages as $page) {
            $pageContent = $page->content;
            $updatedContent = str_replace($searchValue, $replaceValue, json_encode($pageContent));
            $page->content = json_decode($updatedContent);
            $page->save();
        }

        echo '✔️ Updated Unlimited Wash Club Contact Us links on '.count($pages).' page'.(count($pages) === 1 ? '' : 's').".\n";
    }
}
