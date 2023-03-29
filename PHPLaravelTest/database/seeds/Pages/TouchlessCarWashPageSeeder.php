<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class TouchlessCarWashPageSeeder extends Seeder
{
    use FileCopyTrait;
    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/touchless-wash.jpg',
            'touchless_1' => 'Pages/images/touchless-wash/touchless-wash001.jpg',
            'touchless_2' => 'Pages/images/touchless-wash/touchless-wash002.jpg',
            'quote' => 'Pages/images/touchless-wash/quote-avatar-gregory.png',
            'sponsors_1' => 'Pages/images/sponsor-logos/enviroplate.png',
            'sponsors_2' => 'Pages/images/sponsor-logos/colorshine.png',
            'sponsors_3' => 'Pages/images/sponsor-logos/chassisarmor.png',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        $parentPage = Page::where('slug', 'about-our-washes')->firstOrFail();

        Page::create([
            'parent_page_id' => $parentPage->id,
            'slug' => 'touchless-car-wash',
            'category' => 'main',
            'template' => 'about-our-washes.touchless-car-wash',
            'title' => 'Touchless Wash',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => 'A One-of-a-kind Car Wash Experience',
                    'image' => $destImages['hero'],
                ],
                'intro' => (object)[
                    'heading' => 'Touchless Wash: Easy & Stress-Free',
                    'paragraphs' => 'For large vehicles, or those who prefer a touch-free washing experience, the touchless wash offers a superior experience.',
                ],
                'contentBlocks' => [
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::IMAGES_AND_TEXT,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_BLUE,
                        'icon' => 'touchless',
                        'images' => [
                            $destImages['touchless_1'],
                            $destImages['touchless_2'],
                        ],
                        'heading' => 'Touchless Wash',
                        'wysiwyg' => "Brown Bear Car Wash Touchless car washes offer a cleaning option to accommodate customers that can’t wash in a conveyorized tunnel, such as vehicles with ladders or bike racks, or for customers who would prefer to only wash with water and cleaning solutions that touch their vehicle.By combining state of the art technology and our cleaning solutions that are specially formulated for Northwest driving conditions, our Touchless car washes provide customers with a cleaning option to care for nearly any type of vehicle.",
                        'button' => (object)[
                            'route' => 'locations.index',
                            'text' => 'Find a Touchless Wash',
                        ],
                    ],
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::QUOTE,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_WHITE,
                        'attribution' => 'Gregory, Everett',
                        'image' => $destImages['quote'],
                        'quote' => "I won’t trust anything else with my large vehicles. Brown Bear’s Touchless Wash is amazingly effective at cleaning my truck.",
                    ],
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::ACCORDION,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_BLUE,
                        'heading' => 'Our Wash Levels',
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
                ],
                'washGreen' => (object)[
                    'heading' => 'Good for your car, and the earth.',
                    'paragraphs' => "### Touchless washing is green, and better for the earth than home washing.
                        \n\nMany corrosive and toxic substances get deposited on streets and highways and end up on cars.  When vehicles are washed in a driveway, alley, or parking lot, those contaminants are often swept straight into storm drains.  Unfortunately, storm drains are not part of the wastewater system, and whatever enters them from city streets empties directly into the nearest body of water.
                        \n\nBrown Bear Car Wash protects the environment by capturing heavy metals, petroleum products and soap washed off cars before discharging the wastewater into the sewer system for more treatment.  This year, we will pay to safely dispose of more than 500 tons of toxic \"sludge\"—almost 5 ounces for every car washed.  Going even further, we only use non-caustic, non-acidic, non-corrosive and phosphate-free washing solutions.
                        ### Touchless washing is great for your vehicle’s life span.
                        \n\nScientific studies have shown that rags and sponges used in at-home washing inevitably become impregnated with tiny particles of grit that can scratch vehicle surfaces.  What's more, due to inadequate water pressure, most garden hoses leave soap residue that can \"bake\" into the finish and work their way into the paint for days afterward.
                        \n\nBrown Bear Car Wash uses washing methods and materials that are consistent with the recommendations contained in the Owner's Manuals of leading domestic and foreign auto manufacturers.  Why do manufacturers recommend these methods?  Because they have proven effective at preserving the finish—and resale value—of your car, truck, van, or SUV.
                    ",
                    'button' => (object)[
                        'route' => 'community-commitment.wash-green',
                        'text' => 'Learn More About Wash Green',
                    ],
                ],
            ],
        ]);
    }
}
