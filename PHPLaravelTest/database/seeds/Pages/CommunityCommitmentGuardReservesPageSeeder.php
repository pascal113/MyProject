<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class CommunityCommitmentGuardReservesPageSeeder extends Seeder
{
    use FileCopyTrait;

    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/troops.jpg',
            'main1' => 'Pages/images/community-commitment/reserve-gaurd.jpg',
            'main2' => 'Pages/images/community-commitment/esgr.jpg',
            'quote' => 'Pages/images/quote-avatar-alex.png',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        $parentPage = Page::where('slug', 'community-commitment')->firstOrFail();

        Page::create([
            'parent_page_id' => $parentPage->id,
            'slug' => 'guard-reserves',
            'category' => 'main',
            'template' => 'community-commitment.guard-reserves',
            'title' => 'Guard & Reserve Support',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => 'Brown Bear Supports Our Troops!',
                    'image' => $destImages['hero'],
                ],
                'intro' => (object)[
                    'heading' => 'Guard & Reserve Support',
                    'paragraphs' => 'We are proud to support our guard and reserve troops through membership in ESGR, Employer Support of Guard and Reserve. Many of our employees have been military members, and we have a great deal of respect for the level of commitment required.',
                ],
                'contentBlocks' => [
                    (object)[

                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::IMAGES_AND_TEXT,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_BLUE,
                        'images' => [
                            $destImages['main1'],
                            $destImages['main2'],
                        ],
                        'heading' => 'We Support Guard & Reserve',
                        'wysiwyg' => "We proudly support Employer Support of the Guard and Reserve (ESGR). ESGR, was established in 1972 to promote cooperation between members and their employers and to assist in the resolution of conflicts arising from an employee's military commitment.\n\n"
                            ."ESGR has served our country for more than 40 years, fostering a culture in which all employers support and value the employment and military service of members of the National Guard and Reserve in the United States. These citizen warriors could not defend and protect us at home and abroad without the continued promise of meaningful civilian employment for themselves and their families.\n\n"
                            ."ESGR has continued to adapt to meet the needs of Reserve Component members, their families and America’s employers by joining forces with a network of other national, state and local government and professional trade organizations as together, We All Serve!\n\n",
                    ],
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::QUOTE,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_WHITE,
                        'image' => $destImages['quote'],
                        'quote' => 'Brown Bear was a great supporter of mine during my military service. I really appreciate the flexibility they showed me.',
                        'attribution' => 'Alex, Seattle',
                    ],
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::ICON_AND_PARAGRAPH,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_WHITE,
                        'heading' => 'Employment Commitment',
                        'icon' => 'people-group',
                        'introParagraph' => "Brown Bear Car Wash is committed to supporting our troops including our Guard and Reserve members.",
                        'wysiwyg' => "### Flexible Schedules\n"
                            ."Our employees enjoy flexible schedules.\n\n"
                            ."### Flexible Employment\n"
                            ."Our employees enjoy scheduling around military commitments so they can defend our country at home and abroad.\n\n"
                            ."### Positive Culture\n"
                            ."Our work culture is positive, encouraging and fun. Our culture helps military service members balance the demands of their service with a profitable career.\n\n",
                    ],
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::ICON_AND_PARAGRAPH,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_BLUE,
                        'heading' => 'Current Openings',
                        'icon' => 'doc',
                        'wysiwyg' => 'To view current openings please check our our listings on our HR portal. Thank you for your interest in Brown Bear Car Wash!',
                        'button' => (object)[
                            'route' => 'about-our-washes',
                            'text' => 'View Our Current Openings',
                        ],
                    ],
                ],
            ],
        ]);
    }
}
