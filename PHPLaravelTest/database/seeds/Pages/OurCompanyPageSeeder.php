<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class OurCompanyPageSeeder extends Seeder
{
    use FileCopyTrait;

    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/our-company.jpg',
            'ourHistory' => 'Pages/images/our-company/history.jpg',
            'leader0' => 'Pages/images/our-company/leadership/team001.png',
            'leader1' => 'Pages/images/our-company/leadership/team002.png',
            'leader2' => 'Pages/images/our-company/leadership/team003.png',
            'leader3' => 'Pages/images/our-company/leadership/team004.png',
            'leader4' => 'Pages/images/our-company/leadership/team005.png',
            'leader5' => 'Pages/images/our-company/leadership/team006.png',
            'leader6' => 'Pages/images/our-company/leadership/team007.png',
            'leader7' => 'Pages/images/our-company/leadership/team008.png',
            'leader8' => 'Pages/images/our-company/leadership/team009.png',
            'careers' => 'Pages/images/our-company/careers.jpg',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        Page::create([
            'slug' => 'our-company',
            'category' => 'main',
            'template' => 'our-company.index',
            'title' => 'Our Company',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => 'A fixture in Puget Sound since 1957!',
                    'image' => $destImages['hero'],
                ],
                'intro' => (object)[
                    'heading' => 'Welcome to Brown Bear!',
                    'paragraphs' => 'With over 60 convenient Puget Sound locations Brown Bear Car Wash is here to keep your car looking great.',
                ],
                'ourHistory' => (object)[
                    'heading' => 'Our History',
                    'image' => $destImages['ourHistory'],
                    'paragraphs' => "Brown Bear Car Wash began in Seattle in 1957. Victor Odermat opened the first Brown Bear Car Wash on 15th Avenue West in Seattle's Interbay neighborhood, and that location is still in operation today.",
                    'button' => (object)[
                        'route' => 'our-company.our-history',
                        'text' => 'Read About Our History',
                    ],
                ],
                'leadership' => (object)[
                    'heading' => 'Leadership',
                    'headshots' => [
                        $destImages['leader0'],
                        $destImages['leader1'],
                        $destImages['leader2'],
                        $destImages['leader3'],
                        $destImages['leader4'],
                        $destImages['leader5'],
                        $destImages['leader6'],
                        $destImages['leader7'],
                        $destImages['leader8'],
                    ],
                    'paragraphs' => 'Our company has some of the smartest and most capable leaders in the industry working right here in Seattle.',
                    'button' => (object)[
                        'route' => 'about-our-washes.index',
                        'text' => 'Meet the Leadership Team',
                    ],
                ],
                'careers' => (object)[
                    'heading' => 'Careers',
                    'image' => $destImages['careers'],
                    'paragraphs' => "We have provided personal growth and career opportunities for three generations of Puget Sound area residents. In fact, a number of the region's leading business and civic leaders first worked as car washers at Brown Bear Car Wash.",
                    'button' => (object)[
                        'route' => 'our-company.careers',
                        'text' => 'Read About Careers',
                    ],
                ],
                'inTheNews' => (object)[
                    'heading' => 'In the News',
                    'button' => (object)[
                        'route' => 'our-company.news',
                        'text' => 'In the News',
                    ],
                ],
                'pressCenter' => (object)[
                    'heading' => 'Press Center',
                    'icon' => 'press',
                    'paragraphs' => 'We are happy to answer questions about our company or programs. Please reach out to someone in our Press Center to get questions answered or to be out in touch with appropriate staff.',
                    'button' => (object)[
                        'route' => 'about-our-washes.index',
                        'text' => 'Press Center',
                    ],
                ],
            ],
        ]);
    }
}
