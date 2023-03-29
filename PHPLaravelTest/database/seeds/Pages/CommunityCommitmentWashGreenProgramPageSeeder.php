<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class CommunityCommitmentWashGreenPageSeeder extends Seeder
{
    use FileCopyTrait;

    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/forest.jpg',
            'videoPoster' => 'Pages/images/wash-green/video-poster.png',
            'protecting_env1' => 'Pages/images/logo-wash-clean.png',
            'protecting_env2' => 'Pages/images/logo-water-savers.png',
            'clean_car' => 'Pages/images/wash-green/clean-car-diagram.jpg',
            'quote' => 'Pages/images/quote-avatar-mallory.png',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        $parentPage = Page::where('slug', 'community-commitment')->firstOrFail();

        Page::create([
            'parent_page_id' => $parentPage->id,
            'slug' => 'wash-green',
            'category' => 'main',
            'template' => 'community-commitment.wash-green',
            'title' => 'Wash Green®',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => 'Brown Bear is committed to protecting our environment.',
                    'image' => $destImages['hero'],
                ],
                'intro' => (object)[
                    'heading' => 'Wash Green®',
                    'paragraphs' => 'Not only do we take great care of your car, but we also strive to take great care of the environment. Learn more about why Brown Bear is so green and how washing with us can be good for salmon.',
                ],
                'main' => (object)[
                    'heading' => 'Good for your car, and the earth.',
                    'videoUrl' => 'https://s3-us-west-2.amazonaws.com/brown-bear-redesign-production/wash-green.mp4',
                    'videoPosterImage' => $destImages['videoPoster'],
                    'wysiwyg' => "### Our state-of-the-art equipment protects your vehicle’s finish from abrasion\n\n"
                        ."When you hand-wash your car, grit and abrasive particles become embedded in the sponge or rag that you use. Over time, this causes microscopic scratches that degrade your car's paint, harming its appearance and impacting resale value. Our carefully maintained equipment reduces the wear and tear on your car's finish that is common with hand washing. In fact, we have a full-time crew of trained maintenance technicians to ensure our equipment is always working at its best!\n\n"
                        ."### Eco-friendly detergents are formulated specifically for our region\n\n"
                        ."Our specially formulated, environmentally friendly, non-caustic cleaning solutions will get your car sparkling clean, not to mention they are biodegradable and contain no phosphates or caustic solutions. And our car wash equipment delivers the water pressure necessary to rinse away detergent film.\n\n"
                        ."### Automakers recommend against hand washing\n\n"
                        ."Sponges and rags can easily collect and retain tiny bits of abrasive particles. Accordingly, hand washing can be like taking fine sandpaper to your car's paint. Plus, your garden hose may not produce the volume of water or the pressure needed to rinse away detergents completely. At Brown Bear we use the same methods prescribed in the owner's manuals of leading auto manufactures including Ford, General Motors, BMW, Lexus, Infiniti, Mercedes-Benz, Porsche, Chrysler and others.\n\n",
                ],
                'environmentalBenefits' => (object)[
                    'heading' => 'Protecting Our Environment',
                    'paragraphs' => 'Did you know, Wash Green® car wash technology helps to protect the environment in specific ways?',
                    'icon' => 'cloudy',
                    'items' => [
                        (object)[
                            'heading' => 'Safely Disposing of Sludge',
                            'image' => $destImages['protecting_env1'],
                            'wysiwyg' => "When you wash your car in your driveway the run off can end up in the storm drain system, where it receives no treatment at all. If this occurs, what comes off your car can end up in local waterways like Lake Washington or Puget Sound. When you wash your car at Brown Bear, we'll safely dispose of the run off for you!\n\n",
                        ],
                        (object)[
                            'heading' => 'Using Less Water',
                            'image' => $destImages['protecting_env2'],
                            'wysiwyg' => "Commercial car washing is the \"greenest\" way to wash your car. Our fine-tuned car wash machinery and pumps are calibrated to deliver the best car wash using the least water. We are constantly improving our car washes, and have recently upgraded our water reclaimation systems to use less and recycle even more water!\n\n"
                                ."We are a proud member of the **[ICA WaterSavers Program](https://www.carwash.org/for-operators/watersavers)**.",
                        ],
                    ],
                ],
                'additionalEnvironmentalBenefits' => (object)[
                    'heading' => 'Cleaner Car, Cleaner Water!',
                    'image' => $destImages['clean_car'],
                    'paragraphs' => "There's a lot of potentially hazardous stuff that can build up on your car—heavy metals, phosphates, motor oil, antifreeze and other hazardous substances. If you are not careful, all of this can go straight into the storm drain system (which doesn't receive any treatment) and in many cases goes directly into Puget Sound and other local waterways and has been proven to harm salmon, trout and other marine life.\n\nThis is a problem that has been recognized nationwide, and a study by Environmental Partners Inc. confirms that water quality in the Puget Sound region is affected by curbside car washing. We separate road pollutants including oils, heavy metals and antifreeze from the car wash wastewater we discharge. Once we've removed many of the potentially hazardous substances from the water we use, wastewater is then released into the sewer treatment system, not storm water systems, for further cleaning.\n\n",
                    'heading1' => 'Keeping Pollutants Out of Waterways',
                ],
                'quote' => (object)[
                    'image' => $destImages['quote'],
                    'quote' => 'I was thrilled to hear that Brown Bear pays to dispose of 500 tons of potentially hazardous sludge annually. That is nearly 5oz of sludge for every car washed!',
                    'attribution' => 'Mallory, Seattle',
                ],
            ],
        ]);
    }
}
