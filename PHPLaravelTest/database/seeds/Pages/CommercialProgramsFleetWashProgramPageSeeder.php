<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class CommercialProgramsFleetWashProgramPageSeeder extends Seeder
{
    use FileCopyTrait;

    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/tunnel-wash.jpg',
            'main_image1' => 'Pages/images/commercial/fleet-wash.jpg',
            'main_image2' => 'Pages/images/tunnel-wash/tunnel-wash002.jpg',
            'quote_image' => 'Pages/images/quote-avatar-alex.png',
            'sponsors_1' => 'Pages/images/sponsor-logos/enviroplate.png',
            'sponsors_2' => 'Pages/images/sponsor-logos/colorshine.png',
            'sponsors_3' => 'Pages/images/sponsor-logos/chassisarmor.png',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        $parentPage = Page::where('slug', 'commercial-programs')->firstOrFail();

        Page::create([
            'parent_page_id' => $parentPage->id,
            'slug' => 'fleet-wash-program',
            'category' => 'main',
            'template' => 'commercial-programs.fleet-wash-program',
            'title' => 'Fleet Wash Program',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => 'Partner with Brown Bear to keep your fleet looking great!',
                    'image' => $destImages['hero'],
                ],
                'intro' => (object)[
                    'heading' => 'Fleet Wash Program',
                    'paragraphs' => 'Keep your corporate or municipal vehicles looking great while protecting the environment by joining our Feet Wash Program.',
                ],
                'contentBlocks' =>
                [
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::IMAGES_AND_TEXT,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_BLUE,
                        'images' => [
                            $destImages['main_image1'],
                            $destImages['main_image2'],
                        ],
                        'heading' => 'Have you ever considered a fleet wash program?',
                        'wysiwyg' => "Our fleet wash program allows access to any of our 26 tunnel wash locations throughout Puget Sound. Easy monthly invoicing and automatic RFID ticket scanning makes it more convenient than ever. You will have central control of your fleet maintenance. Monthly detailed reports are available to track washes.\n\n"
                            ."We offer three different wash levels to meet your needs. There is no commitment, and your membership can be setup monthly.\n\n"
                            ."If you wash your vehicles often we offer discounts. Just wash at least 25 times across the fleet to get better pricing!\n\n",
                    ],
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::QUOTE,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_WHITE,
                        'attribution' => 'Alex, Seattle',
                        'image' => $destImages['quote_image'],
                        'quote' => 'The Fleet Wash program through Brown Bear is excellent. I have no problem keeping my vehicles looking fantastic.',
                    ],
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::ACCORDION,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_WHITE,
                        'heading' => 'Fleet Wash Levels',
                        'introParagraph' => 'Fleet wash members can use any Puget Sound tunnel wash location unlimited times, for a single monthly price. During that service, one of the following wash club service levels applies.',
                        'items' => [
                            (object)[
                                'heading' => 'Beary Best',
                                'images' => [
                                    $destImages['sponsors_1'],
                                    $destImages['sponsors_2'],
                                    $destImages['sponsors_3'],
                                ],
                                'wysiwyg' => "* **EnviroPlate®** Clear Coat Protectant\n* **Color Shine®** Tri-Color Polish®\n* **Chassis Armor®** Undercarriage Wash, Rust Inhibitor & Sealant\n* Soft Cloth Exterior Wash\n* Spot-Free Rinse\n* Air-Dry",
                            ],
                            (object)[
                                'heading' => 'Beary Bright',
                                'images' => [
                                    $destImages['sponsors_1'],
                                    $destImages['sponsors_2'],
                                    $destImages['sponsors_3'],
                                ],
                                'wysiwyg' => "* **EnviroPlate®** Clear Coat Protectant\n* **Color Shine®** Tri-Color Polish®\n* **Chassis Armor®** Undercarriage Wash, Rust Inhibitor & Sealant\n* Soft Cloth Exterior Wash\n* Spot-Free Rinse\n* Air-Dry",
                            ],
                            (object)[
                                'heading' => 'Beary Clean',
                                'images' => [
                                    $destImages['sponsors_1'],
                                    $destImages['sponsors_2'],
                                    $destImages['sponsors_3'],
                                ],
                                'wysiwyg' => "* **EnviroPlate®** Clear Coat Protectant\n* **Color Shine®** Tri-Color Polish®\n* **Chassis Armor®** Undercarriage Wash, Rust Inhibitor & Sealant\n* Soft Cloth Exterior Wash\n* Spot-Free Rinse\n* Air-Dry",
                            ],
                        ],
                    ],
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::CALL_TO_ACTION,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_BLUE,
                        'heading' => 'Interested in getting started?',
                        'paragraphs' => 'Contact us to get more information about the Car Dealership Program.',
                        'button' => (object)[
                            'route' => 'support.contact-us',
                            'text' => 'Request Information',
                            'queryParams' => '?regarding=Programs Inquiry&program=Fleet Wash Program&show=email',
                        ],
                    ],
                ],
            ],
        ]);
    }
}
