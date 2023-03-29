<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class WashClubsForHireUnlimitedWashClubPageSeeder extends Seeder
{
    use FileCopyTrait;

    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/yellow-cab.jpg',
            'main1' => 'Pages/images/wash-clubs/for-hire-unlimited-wash-club.jpg',
            'main2' => 'Pages/images/community-commitment/unlimited-wash002.jpg',
            'sponsors_1' => 'Pages/images/sponsor-logos/enviroplate.png',
            'sponsors_2' => 'Pages/images/sponsor-logos/colorshine.png',
            'sponsors_3' => 'Pages/images/sponsor-logos/chassisarmor.png',
            'tunnel' => 'Pages/images/img-map.png',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        $parentPage = Page::where('slug', 'wash-clubs')->firstOrFail();

        Page::create([
            'parent_page_id' => $parentPage->id,
            'slug' => 'for-hire-unlimited-wash-club',
            'category' => 'main',
            'template' => 'wash-clubs.for-hire-unlimited-wash-club',
            'title' => 'For Hire Unlimited Wash Club',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => 'Unlimited tunnel washes your Uber, Lyft or Taxi Cab looking great!',
                    'image' => $destImages['hero'],
                ],
                'intro' => (object)[
                    'heading' => 'For Hire Unlimited Wash Club',
                    'paragraphs' => 'If you drive a Lyft, Uber or Taxi service around Puget Sound, you know how important it is to keep your vehicle clean and in good shape. ',
                ],

                'main' => (object)[
                    'images' => [
                        $destImages['main1'],
                        $destImages['main2'],
                    ],
                    'heading' => 'What is For Hire Unlimited Wash Club?',
                    'paragraphs' => 'For Hire Unlimited Wash Club makes maintenance a breeze. Simply enroll in our monthly plan, and breeze into one of our many Puget Sound tunnel wash locations between pickups or before you start your day.',
                    'callToAction' => 'Let’s get started with a quick introduction to For Hire Unlimited Wash Club Wash levels!',
                ],
                'washClubLevels' => (object)[
                    'heading' => 'For Hire Unlimited Wash Club Levels',
                    'paragraphs' => 'Unlimited Wash Club members can use any Puget Sound tunnel wash location unlimited times, for a single monthly price. During that service, one of the following wash club service levels applies.',
                    'levels' => [
                        (object)[
                            'title' => 'Beary Best',
                            'images' => [
                                $destImages['sponsors_1'],
                                $destImages['sponsors_2'],
                                $destImages['sponsors_3'],
                            ],
                            'details' => "* **EnviroPlate®** Clear Coat Protectant\n* **Color Shine®** Tri-Color Polish®\n* **Chassis Armor®** Undercarriage Wash, Rust Inhibitor & Sealant\n* Soft Cloth Exterior Wash\n* Spot-Free Rinse\n* Air-Dry",
                        ],
                        (object)[
                            'title' => 'Beary Bright',
                            'images' => [
                                $destImages['sponsors_1'],
                                $destImages['sponsors_2'],
                                $destImages['sponsors_3'],
                            ],
                            'details' => "* **EnviroPlate®** Clear Coat Protectant\n* **Color Shine®** Tri-Color Polish®\n* **Chassis Armor®** Undercarriage Wash, Rust Inhibitor & Sealant\n* Soft Cloth Exterior Wash\n* Spot-Free Rinse\n* Air-Dry",
                        ],
                        (object)[
                            'title' => 'Beary Clean',
                            'images' => [
                                $destImages['sponsors_1'],
                                $destImages['sponsors_2'],
                                $destImages['sponsors_3'],
                            ],
                            'details' => "* **EnviroPlate®** Clear Coat Protectant\n* **Color Shine®** Tri-Color Polish®\n* **Chassis Armor®** Undercarriage Wash, Rust Inhibitor & Sealant\n* Soft Cloth Exterior Wash\n* Spot-Free Rinse\n* Air-Dry",
                        ],

                    ],
                    'paragraphs2' => 'Have your perfect wash level in mind? Check out our purchasing options below!',
                ],
                'purchasingOptions' => (object)[
                    'heading' => 'Purchasing Options',
                    'paragraphs' => 'Unlimited Wash Club Memberships are for sale on-site at any of our Tunnel Wash Locations, or, you can purchase them here on our site. Wash levels and associated pricing is listed below.',
                    'products' => [
                        (object)['id' => 17],
                        (object)['id' => 18],
                        (object)['id' => 19],
                    ],

                ],
                'findTunnelWash' => (object)[
                    'heading' => 'Find a Tunnel Wash!',
                    'paragraphs' => 'Once you have your For Hire Unlimited Wash Club Membership, or know which one you would like to buy, all you need to do is find a Tunnel Wash!',
                    'image' => $destImages['tunnel'],
                    'button' => (object)[
                        'route' => 'locations.index',
                        'text' => 'Find a Wash',
                    ],
                ],
                'washGreen' => (object)[
                    'heading' => 'Good for your car, and the earth.',
                    'wysiwyg' => "### Tunnel washing is green, and better for the earth than home washing.\n\nMany corrosive and toxic substances get deposited on streets and highways and end up on cars.  When vehicles are washed in a driveway, alley, or parking lot, those contaminants are often swept straight into storm drains.  Unfortunately, storm drains are not part of the wastewater system, and whatever enters them from city streets empties directly into the nearest body of water.\n\nBrown Bear Car Wash protects the environment by capturing heavy metals, petroleum products and soap washed off cars before discharging the wastewater into the sewer system for more treatment.  This year, we will pay to safely dispose of more than 500 tons of toxic \"sludge\"—almost 5 ounces for every car washed.  Going even further, we only use non-caustic, non-acidic, non-corrosive and phosphate-free washing solutions.\n\n### Tunnel washing is great for your vehicle’s life span.\n\nScientific studies have shown that rags and sponges used in at-home washing inevitably become impregnated with tiny particles of grit that can scratch vehicle surfaces.  What's more, due to inadequate water pressure, most garden hoses leave soap residue that can \"bake\" into the finish and work their way into the paint for days afterward.\n\nBrown Bear Car Wash uses washing methods and materials that are consistent with the recommendations contained in the Owner's Manuals of leading domestic and foreign auto manufacturers.  Why do manufacturers recommend these methods?  Because they have proven effective at preserving the finish—and resale value—of your car, truck, van, or SUV.",
                    'button' => (object)[
                        'route' => 'community-commitment.wash-green',
                        'text' => 'Learn More About Wash Green',
                    ],
                ],
            ],
        ]);
    }
}
