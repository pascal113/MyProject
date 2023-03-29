<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class CommunityCommitmentCharitableDonationsPageSeeder extends Seeder
{
    use FileCopyTrait;

    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/community-commitment-diversity.jpg',
            'main_image1' => 'Pages/images/community-commitment/woodland-zoo.jpg',
            'main_image2' => 'Pages/images/community-commitment/bear-cubs.jpg',
            'quote_image' => 'Pages/images/quote-avatar-mallory.png',
            'community_image1' => 'Pages/images/community-logos/woodland.png',
            'community_image2' => 'Pages/images/community-logos/camp-korey.png',
            'community_image3' => 'Pages/images/community-logos/fred-hutch.png',
            'community_image4' => 'Pages/images/community-logos/honor-flight.png',
            'community_image5' => 'Pages/images/community-logos/marine-corps.png',
            'community_image6' => 'Pages/images/community-logos/mountains-to-sound.png',
            'community_image7' => 'Pages/images/community-logos/moyer-foundation.png',
            'community_image8' => 'Pages/images/community-logos/puget-soundkeeper.png',
            'community_image9' => 'Pages/images/community-logos/usmc-support.png',
            'community_image10' => 'Pages/images/community-logos/uso.png',
            'community_image11' => 'Pages/images/community-logos/virginia-mason.png',
            'community_image12' => 'Pages/images/community-logos/wild-salmon.png',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        $parentPage = Page::where('slug', 'community-commitment')->firstOrFail();

        Page::create([
            'parent_page_id' => $parentPage->id,
            'slug' => 'charitable-donations',
            'category' => 'main',
            'template' => 'community-commitment.charitable-donations',
            'title' => 'Charitable Donations',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => 'Brown Bear supports the Puget Sound community through donations.',
                    'image' => $destImages['hero'],
                ],
                'intro' => (object)[
                    'heading' => 'Charitable Donations',
                    'paragraphs' => 'Brown Bear Car Wash is proud to support charitable organizations that make a difference in our community.',
                ],
                'contentBlocks' => [
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::IMAGES_AND_TEXT,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_BLUE,
                        'images' => [
                            $destImages['main_image1'],
                            $destImages['main_image2'],
                        ],
                        'heading' => 'Woodland Park Zoo Bear Cam!',
                        'wysiwyg' => 'We have been long term supporters of the Woodland Park Zoo and join with them in many of their environmental endeavors. Check out the Brown Bear exhibit at the zoo on their live Bear Cam.',
                        'button' => (object)[
                            'route' => 'https://www.zoo.org/bearcam',
                            'text' => 'View the Bear Cam',
                        ],
                    ],
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::QUOTE,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_WHITE,
                        'image' => $destImages['quote_image'],
                        'quote' => 'Brown Bear really makes a difference in the Puget Sound Community. You can feel their commitment to the people of this area.',
                        'attribution' => 'Mallory, Seattle',
                    ],
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::ICON_PARAGRAPH_AND_IMAGES,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_WHITE,
                        'heading' => 'Supporting Our Community',
                        'paragraphs' => 'Brown Bear is proud to support organizations making a difference in our community. Environmental, educational and social change causes are among our most supported.',
                        'icon' => 'hands-heart',
                        'images' => (object)[
                            'items' => [
                                (object)[
                                    'image' => $destImages['community_image1'],
                                    'url' => (object)[
                                        'value' => 'http://brownbear.com',
                                        'openInNewTab' => true,
                                    ],
                                ],
                                (object)[
                                    'image' => $destImages['community_image2'],
                                ],
                                (object)[
                                    'image' => $destImages['community_image3'],
                                    'url' => (object)[
                                        'value' => 'http://brownbear.com',
                                        'openInNewTab' => true,
                                    ],
                                ],
                                (object)[
                                    'image' => $destImages['community_image4'],
                                ],
                                (object)[
                                    'image' => $destImages['community_image5'],
                                    'url' => (object)[
                                        'value' => 'http://brownbear.com',
                                        'openInNewTab' => true,
                                    ],
                                ],
                                (object)[
                                    'image' => $destImages['community_image6'],
                                ],
                                (object)[
                                    'image' => $destImages['community_image7'],
                                    'url' => (object)[
                                        'value' => 'http://brownbear.com',
                                        'openInNewTab' => true,
                                    ],
                                ],
                                (object)[
                                    'image' => $destImages['community_image8'],
                                ],
                                (object)[
                                    'image' => $destImages['community_image9'],
                                    'url' => (object)[
                                        'value' => 'http://brownbear.com',
                                        'openInNewTab' => true,
                                    ],
                                ],
                                (object)[
                                    'image' => $destImages['community_image10'],
                                ],
                                (object)[
                                    'image' => $destImages['community_image11'],
                                    'url' => (object)[
                                        'value' => 'http://brownbear.com',
                                        'openInNewTab' => true,
                                    ],
                                ],
                                (object)[
                                    'image' => $destImages['community_image12'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
