<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class CommunityCommitmentDiversityInclusionPageSeeder extends Seeder
{
    use FileCopyTrait;

    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/community-commitment-diversity.jpg',
            'main1' => 'Pages/images/community-commitment/diversity.jpg',
            'main2' => 'Pages/images/community-commitment/diversity-hands.jpg',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        $parentPage = Page::where('slug', 'community-commitment')->firstOrFail();

        Page::create([
            'parent_page_id' => $parentPage->id,
            'slug' => 'diversity-inclusion',
            'category' => 'main',
            'template' => 'community-commitment.diversity-inclusion',
            'title' => 'Diversity & Inclusion',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => 'Brown Bear is committed to diversity in our organization.',
                    'image' => $destImages['hero'],
                ],
                'intro' => (object)[
                    'heading' => 'Diversity & Inclusion',
                    'paragraphs' => 'Car Wash Enterprises, Inc., the parent company of Brown Bear Car Wash, believes that every employee has the right to work in surroundings that are free from all forms of unlawful discrimination.',
                ],
                'contentBlocks' => [
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::IMAGES_AND_TEXT,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_BLUE,
                        'icon' => 'images/community-commitment/logo-e-verify.png',
                        'iconType' => 'image',
                        'images' => [
                            $destImages['main1'],
                            $destImages['main2'],
                        ],
                        'heading' => 'Car Wash Enterprises is an Equal Opportunity Employer',
                        'wysiwyg' => "Car Wash Enterprises, Inc., the parent company of Brown Bear Car Wash, believes that every employee has the right to work in surroundings that are free from all forms of unlawful discrimination. It is the Company's policy to hire, promote, transfer, terminate, and make all other employment-related decisions without regard to an employee's sex, sexual orientation, race, color, creed, religion, age, national origin, disability, citizenship, marital or veteran status, or any other protected characteristic under local, state, or federal law.\n\n"
                            ."We participate in the E-Verify program, which is an Internet-based system that compares information from an employee's Form I-9, Employment Eligibility Verification, to data from U.S. Department of Homeland Security and Social Security Administration records to confirm employment eligibility.\n\n",
                    ],
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::ICON_AND_PARAGRAPH,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_WHITE,
                        'heading' => 'Employment Commitment',
                        'icon' => 'people-group',
                        'introParagraph' => "Brown Bear Car Wash is committed to supporting diversity and inclusion in our workplace environment.",
                        'wysiwyg' => "### Merit-Based Hiring\n"
                            ."It is the Company's policy to hire, promote, transfer, terminate, and make all other employment-related decisions without regard to an employee's sex, sexual orientation, race, color, creed, religion, age, national origin, disability, citizenship, marital or veteran status\n\n"
                            ."### Free From Discrimination\n"
                            ."Every employee has the right to work in surroundings that are free from all forms of unlawful discrimination.\n\n"
                            ."### Independently Verified\n"
                            ."We participate in the E-Verify program, which is an Internet-based system that compares information from an employee's Form I-9, Employment Eligibility Verification, to data from U.S. Department of Homeland Security and Social Security Administration records to confirm employment eligibility.\n\n",
                    ],
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::CALL_TO_ACTION,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_BLUE,
                        'heading' => 'Current Openings',
                        'icon' => 'doc',
                        'paragraphs' => 'To view current openings please check our our listings on our HR portal. Thank you for your interest in Brown Bear Car Wash!',
                        'button' => (object)[
                            'route' => 'our-company.careers',
                            'text' => 'View Our Current Openings',
                        ],
                    ],
                ],
            ],
        ]);
    }
}
