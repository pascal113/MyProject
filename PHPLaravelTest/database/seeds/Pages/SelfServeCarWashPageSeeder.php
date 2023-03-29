<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class SelfServeCarWashPageSeeder extends Seeder
{
    use FileCopyTrait;
    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/self-serve-wash.jpg',
            'service_1' => 'Pages/images/self-serve-wash/self-serve-wash001.jpg',
            'service_2' => 'Pages/images/self-serve-wash/self-serve-wash002.jpg',
            'quote' => 'Pages/images/self-serve-wash/quote-avatar-marcus.png',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        $parentPage = Page::where('slug', 'about-our-washes')->firstOrFail();

        Page::create([
            'parent_page_id' => $parentPage->id,
            'slug' => 'self-serve-car-wash',
            'category' => 'main',
            'template' => 'about-our-washes.self-serve-car-wash',
            'title' => 'Self-Serve Wash',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => '25+ Self-Serve locations in Puget Sound!',
                    'image' => $destImages['hero'],
                ],
                'intro' => (object)[
                    'heading' => 'Self-Serve Wash: Effective & Economical',
                    'paragraphs' => 'Self-Serve Washes allow you to clean your car exterior yourself with powerful and well maintained equipment. Our self-serve locations are open throughout the day and are extremely cost-effective.',
                ],
                'contentBlocks' => [
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::IMAGES_AND_TEXT,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_BLUE,
                        'icon' => 'self',
                        'images' => [
                            $destImages['service_1'],
                            $destImages['service_2'],
                        ],
                        'heading' => 'Self-Serve Wash',
                        'wysiwyg' => "At each of our Brown Bear Self-Serve car wash locations across Washington, you’ll find clean, well-kept facilities and an unrivaled commitment to customer service.  We only use gentle cleaning solutions that are specially formulated for Northwest driving conditions. We stand behind the safety of our washes with equipment that is consistently maintained by our own fleet of trained service technicians. Most of our Self-Serve car washes are just $1.00 to start, and various cleaning products (towels, glass wipes, etc.) are available for purchase at on-site vending machines.",
                        'button' => (object)[
                            'route' => 'locations.index',
                            'text' => 'Find a Self-Serve Wash',
                        ],
                    ],
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::QUOTE,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_BLUE,
                        'attribution' => 'Marcus, Seattle',
                        'image' => $destImages['quote'],
                        'quote' => 'It’s so easy to wash my car at a self-serve wash. I can do it whenever I want to. They are open 24 hours per day',
                    ],
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::ICON_AND_PARAGRAPH,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_BLUE,
                        'heading' => 'Your Wash, Your Way',
                        'icon' => 'thumbs-up',
                        'introParagraph' => 'At Brown Bear Self-Serve washes you only pay for the time it takes you to wash your car. We offer many options to customize your wash to be just the way you like it.',
                        'wysiwyg' => "### Low-Pressure Pre-Soak
                        \n\nLoosen tough dirt and grime
                        ### Warm, High-Pressure Soap
                        \n\nUse suds to get that dirt lose.
                        ### Foaming Brush
                        \n\nScrub that gunk away with ease.
                        ### High Pressure Rinse
                        \n\nClean off the soap and dirt.
                        ### Triple Foam Conditioner
                        \n\nProtect your paint job.
                        ### Enviroplate Wax
                        \n\nKeep that shine.",
                    ],
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::VIDEO,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_WHITE,
                        'heading' => 'What’s it like?',
                        'paragraphs' => 'Learn about the self-serve car wash experience at Brown Bear Car Wash and how long it will take.',
                        'videoUrl' => 'https://www.youtube.com/embed/cK7mMGydUcs',
                    ],
                ],
                'washGreen' => (object)[
                    'heading' => 'Good for your car, and the earth.',
                    'paragraphs' => "### Self-serve washing is green, and better for the earth than home washing.
                        \n\nMany corrosive and toxic substances get deposited on streets and highways and end up on cars.  When vehicles are washed in a driveway, alley, or parking lot, those contaminants are often swept straight into storm drains.  Unfortunately, storm drains are not part of the wastewater system, and whatever enters them from city streets empties directly into the nearest body of water.
                        \n\nBrown Bear Car Wash protects the environment by capturing heavy metals, petroleum products and soap washed off cars before discharging the wastewater into the sewer system for more treatment.  This year, we will pay to safely dispose of more than 500 tons of toxic \"sludge\"—almost 5 ounces for every car washed. Going even further, we only use non-caustic, non-acidic, non-corrosive and phosphate-free washing solutions.
                        ### Self-serve washing is great for your vehicle’s life span.
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
