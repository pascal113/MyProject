<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class TunnelCarWashPageSeeder extends Seeder
{
    use FileCopyTrait;
    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/tunnel-wash.jpg',
            'tunnel_1' => 'Pages/images/tunnel-wash/tunnel-wash001.jpg',
            'tunnel_2' => 'Pages/images/tunnel-wash/tunnel-wash002.jpg',
            'quote' => 'Pages/images/tunnel-wash/quote-avatar.png',
            'sponsors_1' => 'Pages/images/sponsor-logos/enviroplate.png',
            'sponsors_2' => 'Pages/images/sponsor-logos/colorshine.png',
            'sponsors_3' => 'Pages/images/sponsor-logos/chassisarmor.png',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        $parentPage = Page::where('slug', 'about-our-washes')->firstOrFail();

        Page::create([
            'parent_page_id' => $parentPage->id,
            'slug' => 'tunnel-car-wash',
            'category' => 'main',
            'template' => 'about-our-washes.tunnel-car-wash',
            'title' => 'Tunnel Wash',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => '25+ Tunnel Wash locations in Puget Sound!',
                    'image' => $destImages['hero'],
                ],
                'intro' => (object)[
                    'heading' => 'Quick & Comfortable',
                    'paragraphs' => 'Tunnel Washes are our most popular car wash format. The easy drive-thru design allows us to quickly clean your car with express exterior cleaning products that protect your car and preserves its value.',
                ],
                'contentBlocks' => [
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::IMAGES_AND_TEXT,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_BLUE,
                        'icon' => 'tunnel',
                        'images' => [
                            $destImages['tunnel_1'],
                            $destImages['tunnel_2'],
                        ],
                        'heading' => 'Tunnel Wash',
                        'wysiwyg' => "We only use gentle, environmentally friendly cleaning detergents that are specially formulated for Northwest driving conditions. In fact, we use the same methods as prescribed in the owner’s manuals of leading manufacturers including Mercedes-Benz, BMW, Porsche, Lexus and others.\n\nOur Tunnel car washes are consistently maintained by our own fleet of trained service technicians to ensure the highest quality car wash and customer experience.",
                        'button' => (object)[
                            'route' => 'locations.index',
                            'text' => 'Find a Tunnel Wash',
                        ],
                    ],
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::QUOTE,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_BLUE,
                        'attribution' => 'Mary, Seattle',
                        'image' => $destImages['quote'],
                        'quote' => "I love the Brown Bear Tunnel Washes! They are super quick, and very effective at cleaning my car. I can get my vehicle looking great before business meetings. It is so convenient. Wow.",
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
                'products' => [
                    'heading' => 'Buy wash packs and save.',
                    'items' => [
                        (object)[
                            'id' => 7,
                        ],
                        (object)[
                            'id' => 8,
                        ],
                        (object)[
                            'id' => 9,
                        ],
                        (object)[
                            'id' => 10,
                        ],
                        (object)[
                            'id' => 11,
                        ],
                        (object)[
                            'id' => 12,
                        ],
                    ],
                ],
                'washGreen' => (object)[
                    'heading' => 'Good for your car, and the earth.',
                    'paragraphs' => "### Tunnel washing is green, and better for the earth than home washing.\n\nMany corrosive and toxic substances get deposited on streets and highways and end up on cars.  When vehicles are washed in a driveway, alley, or parking lot, those contaminants are often swept straight into storm drains.  Unfortunately, storm drains are not part of the wastewater system, and whatever enters them from city streets empties directly into the nearest body of water.\n\nBrown Bear Car Wash protects the environment by capturing heavy metals, petroleum products and soap washed off cars before discharging the wastewater into the sewer system for more treatment.  This year, we will pay to safely dispose of more than 500 tons of toxic \"sludge\"—almost 5 ounces for every car washed.  Going even further, we only use non-caustic, non-acidic, non-corrosive and phosphate-free washing solutions.\n\n### Tunnel washing is great for your vehicle’s life span.\n\nScientific studies have shown that rags and sponges used in at-home washing inevitably become impregnated with tiny particles of grit that can scratch vehicle surfaces.  What's more, due to inadequate water pressure, most garden hoses leave soap residue that can \"bake\" into the finish and work their way into the paint for days afterward.\n\nBrown Bear Car Wash uses washing methods and materials that are consistent with the recommendations contained in the Owner's Manuals of leading domestic and foreign auto manufacturers.  Why do manufacturers recommend these methods?  Because they have proven effective at preserving the finish—and resale value—of your car, truck, van, or SUV.",
                    'button' => (object)[
                        'route' => 'community-commitment.wash-green',
                        'text' => 'Learn More About Wash Green',
                    ],
                ],
            ],
        ]);
    }
}
