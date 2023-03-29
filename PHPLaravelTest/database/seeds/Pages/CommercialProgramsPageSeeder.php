<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class CommercialProgramsPageSeeder extends Seeder
{
    use FileCopyTrait;

    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/ferris-wheel.jpg',
            'section_image1' => 'Pages/images/wash-clubs/for-hire-unlimited-wash-club.jpg',
            'section_image2' => 'Pages/images/commercial/dealership.jpg',
            'customer_quote' => 'Pages/images/quote-avatar-andrea.png',
            'section_image3' => 'Pages/images/commercial/fleet-wash.jpg',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        Page::create([
            'slug' => 'commercial-programs',
            'category' => 'main',
            'template' => 'commercial-programs.index',
            'title' => 'Commercial Programs',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => 'Partner with Brown Bear!',
                    'image' => $destImages['hero'],
                ],
                'intro' => (object)[
                    'heading' => 'Commercial Programs',
                    'paragraphs' => 'If you drive a Lyft, Uber or Taxi service around Puget Sound, you know how important it is to keep your vehicle clean and in good shape. Our For Hire Unlimited Wash Club makes maintenance a breeze. ',
                ],
                'forHireUnlimitedWashClub' => (object)[
                    'image' => $destImages['section_image1'],
                    'heading' => 'For Hire Unlimited Wash Club',
                    'paragraphs' => 'If you drive a Lyft, Uber or Taxi service around Puget Sound, you know how important it is to keep your vehicle clean and in good shape. Our For Hire Unlimited Wash Club makes maintenance a breeze.',
                    'button' => (object)[
                        'route' => 'wash-clubs.for-hire-unlimited-wash-club',
                        'text' => 'For Hire Unlimited Wash Club Details',
                    ],
                ],
                'carDealershipProgram' => (object)[
                    'image' => $destImages['section_image2'],
                    'heading' => 'Car Dealership Program',
                    'paragraphs' => 'The Brown Bear Car Dealership Program lets dealerships bring added value to their customers at a minimal cost by purchasing discounted car wash tickets to use in their service department.',
                    'button' => (object)[
                        'route' => 'commercial-programs.car-dealership-program',
                        'text' => 'Car Dealership Program Details',
                    ],
                ],
                'quote' => (object)[
                    'image' => $destImages['customer_quote'],
                    'quote' => 'Brown Bear is an awesome Seattle company and an iconic brand. My partnership with Brown Bear is very valuable for my company.',
                    'attribution' => 'Andrea, Seattle',
                ],
                'fleetWashProgram' => (object)[
                    'image' => $destImages['section_image3'],
                    'heading' => 'Fleet Wash Program',
                    'paragraphs' => 'Keep your corporate or municipal vehicles looking great while protecting the environment by joining our Feet Wash Program.',
                    'button' => (object)[
                        'route' => 'commercial-programs.fleet-wash-program',
                        'text' => 'Fleet Wash Program Details',
                    ],
                ],
            ],
        ]);
    }
}
