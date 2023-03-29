<?php

declare(strict_types=1);

use App\Models\Page;
use Illuminate\Database\Seeder;

class OurCompanyNewsPageSeeder extends Seeder
{
    use FileCopyTrait;

    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/news.jpg',
            'customer_quote' => 'Pages/images/careers/quote-avatar-jimmy.png',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        $parentPage = Page::where('slug', 'our-company')->firstOrFail();

        Page::create([
            'parent_page_id' => $parentPage->id,
            'slug' => 'news',
            'category' => 'main',
            'template' => 'our-company.news',
            'title' => 'News',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => 'Brown Bear is in the news!',
                    'image' => $destImages['hero'],
                ],
                'intro' => (object)[
                    'heading' => 'In the News',
                    'paragraphs' => 'Brown Bear is in the news, and the headlines are covering a broad range of topics from our environmental commitment to the growth of our locations.',
                ],
                'latestStories' => (object)[
                    'heading' => 'The Latest Stories',
                    'items' => [
                        (object)[
                            'date' => '2019-01-01',
                            'publication' => 'Puget Sound Business Journal',
                            'title' => 'Brown Bear upgrades will speed car wash experience',
                            'url' => (object)['value' => '/'],
                        ],
                        (object)[
                            'date' => '2019-01-01',
                            'publication' => 'Puget Sound Business Journal',
                            'title' => 'Brown Bear upgrades will speed car wash experience',
                            'url' => (object)['value' => '/'],
                        ],
                        (object)[
                            'date' => '2019-01-01',
                            'publication' => 'Puget Sound Business Journal',
                            'title' => 'Brown Bear upgrades will speed car wash experience',
                            'url' => (object)['value' => '/'],
                        ],
                        (object)[
                            'date' => '2019-01-01',
                            'publication' => 'Puget Sound Business Journal',
                            'title' => 'Brown Bear upgrades will speed car wash experience',
                            'url' => (object)['value' => '/'],
                        ],
                        (object)[
                            'date' => '2019-01-01',
                            'publication' => 'Puget Sound Business Journal',
                            'title' => 'Brown Bear upgrades will speed car wash experience',
                            'url' => (object)['value' => '/'],
                        ],
                        (object)[
                            'date' => '2019-01-01',
                            'publication' => 'Puget Sound Business Journal',
                            'title' => 'Brown Bear upgrades will speed car wash experience',
                            'url' => (object)['value' => '/'],
                        ],
                        (object)[
                            'date' => '2019-01-01',
                            'publication' => 'Puget Sound Business Journal',
                            'title' => 'Brown Bear upgrades will speed car wash experience',
                            'url' => (object)['value' => '/'],
                        ],
                        (object)[
                            'date' => '2019-01-01',
                            'publication' => 'Puget Sound Business Journal',
                            'title' => 'Brown Bear upgrades will speed car wash experience',
                            'url' => (object)['value' => '/'],
                        ],
                        (object)[
                            'date' => '2019-01-01',
                            'publication' => 'Puget Sound Business Journal',
                            'title' => 'Brown Bear upgrades will speed car wash experience',
                            'url' => (object)['value' => '/'],
                        ],
                    ],
                ],
                'quote' => (object)[
                    'image' => $destImages['customer_quote'],
                    'quote' => 'Brown Bear is an iconic part of our Seattle community. They offer a nice place to work, and help out with community projects. Who doesn’t love Brown Bear?',
                    'attribution' => 'Jimmy, Seattle',
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
