<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class OurCompanyPressCenterPageSeeder extends Seeder
{
    use FileCopyTrait;
    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/press-center-people.jpg',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        $parentPage = Page::where('slug', 'our-company')->firstOrFail();

        Page::create([
            'parent_page_id' => $parentPage->id,
            'slug' => 'press-center',
            'category' => 'main',
            'template' => 'our-company.press-center',
            'title' => 'Press Center',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => [
                    'heading' => 'There is plenty going on at Brown Bear Car Wash!',
                    'image' => $destImages['hero'],
                ],
                'intro' => [
                    'heading' => 'Press Center',
                    'paragraphs' => 'We are happy to answer questions about our company or programs. Please reach out to someone in our Press Center to get questions answered or to be out in touch with appropriate staff.',
                ],
                'pressContacts' => [
                    'heading' => 'Press Contacts',
                    'icon' => 'press',
                    'email' => 'presscenter@brownbear.com',
                    'phoneNumber' => '206-789-3700',
                ],
            ],
        ]);
    }
}
