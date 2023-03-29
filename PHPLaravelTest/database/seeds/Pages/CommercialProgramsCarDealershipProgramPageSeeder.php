<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class CommercialProgramsCarDealershipProgramPageSeeder extends Seeder
{
    use FileCopyTrait;

    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/dealership.jpg',
            'main1' => 'Pages/images/commercial/dealership.jpg',
            'main2' => 'Pages/images/tunnel-wash/tunnel-wash002.jpg',
            'partner1' => 'Pages/images/commercial/dealer-tickets.png',
            'dealer1' => 'Pages/images/commercial/dealer-logos/dealer-acura-bellevue.png',
            'dealer2' => 'Pages/images/commercial/dealer-logos/dealer-audi-seattle.png',
            'dealer3' => 'Pages/images/commercial/dealer-logos/dealer-bmw-seattle.png',
            'dealer4' => 'Pages/images/commercial/dealer-logos/dealer-carter-subaru.png',
            'dealer5' => 'Pages/images/commercial/dealer-logos/dealer-toyota-lake-city.png',
            'dealer6' => 'Pages/images/commercial/dealer-logos/dealer-vw-university.png',
            'quote' => 'Pages/images/quote-avatar-jennifer.png',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        $parentPage = Page::where('slug', 'commercial-programs')->firstOrFail();

        Page::create([
            'parent_page_id' => $parentPage->id,
            'slug' => 'car-dealership-program',
            'category' => 'main',
            'template' => 'commercial-programs.car-dealership-program',
            'title' => 'Car Dealership Program',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => 'Partner with Brown Bear to set your dealership apart!',
                    'image' => $destImages['hero'],
                ],
                'intro' => (object)[
                    'heading' => 'Car Dealership Program',
                    'paragraphs' => "The Brown Bear Car Dealership Program lets dealerships bring added value to their customers at a minimal cost by purchasing discounted car wash tickets to use in their service department.",
                ],
                'contentBlocks' => [
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::IMAGES_AND_TEXT,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_BLUE,
                        'heading' => 'How can our Car Dealership Program set you apart?',
                        'images' => [
                            $destImages['main1'],
                            $destImages['main2'],
                        ],
                        'wysiwyg' => "The Brown Bear Car Dealership Program lets dealerships bring added value to their customers at a minimal cost by purchasing discounted car wash tickets to use in their service department.\n\n"
                            ."These tickets are fully branded to the dealership and are valid at any Brown Bear Car Wash tunnel location. By offering service customers these tickets, it eliminates the need to wash cars by hand on site and allows the customer to wash their vehicle at their convenience.\n\n"
                            ."* Eliminate delays caused by service customers waiting for their car to be washed\n"
                            ."* Provide your customers with the flexibility of washing when they want\n"
                            ."* Avoid the increasing regulation around wash water management\n"
                            ."* Reduce the need for additional labor costs\n"
                            ."* Expand marketing reach and develop customer loyalty\n"
                            ."* Invest in the dealership instead of car wash equipment\n"
                            ."* Demonstrate commitment to customers and the environment\n",
                    ],
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::QUOTE,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_WHITE,
                        'image' => $destImages['quote'],
                        'quote' => 'At Carter Subaru and Volkswagen one of our goals is to do our part in protecting the environment and wildlife. After evaluating our own car wash systems we realized we wasted so much water and resources by doing our own car washes. It just made sense for us to go with Brown Bear because of the advanced technology and filtration process they use.',
                        'attribution' => 'Jennifer Moran, Carter Motors',
                    ],
                ],
                'participatingDealerships' => (object)[
                    'heading' => 'Participating Dealerships',
                    'paragraphs' => 'We are proud to partner with dealerships to provide environmentally friendly car washing services to their customers.',
                    'subHeading' => 'We offer custom branded wash tickets!',
                    'image' => $destImages['partner1'],
                    'dealershipsSubheading' => 'We have 35+ Dealerships Partners!',
                    'dealershipLogos' => (object)[
                        'items' => [
                            (object)[
                                'url' => (object)[
                                    'value' => null,
                                ],
                                'image' => $destImages['dealer1'],
                            ],
                            (object)[
                                'url' => (object)[
                                    'value' => null,
                                ],
                                'image' => $destImages['dealer2'],
                            ],
                            (object)[
                                'url' => (object)[
                                    'value' => null,
                                ],
                                'image' => $destImages['dealer3'],
                            ],
                            (object)[
                                'url' => (object)[
                                    'value' => null,
                                ],
                                'image' => $destImages['dealer4'],
                            ],
                            (object)[
                                'url' => (object)[
                                    'value' => null,
                                ],
                                'image' => $destImages['dealer5'],
                            ],
                            (object)[
                                'url' => (object)[
                                    'value' => null,
                                ],
                                'image' => $destImages['dealer6'],
                            ],
                            (object)[
                                'url' => (object)[
                                    'value' => null,
                                ],
                                'image' => $destImages['dealer4'],
                            ],
                            (object)[
                                'url' => (object)[
                                    'value' => null,
                                ],
                                'image' => $destImages['dealer5'],
                            ],
                            (object)[
                                'url' => (object)[
                                    'value' => null,
                                ],
                                'image' => $destImages['dealer6'],
                            ],
                        ],
                    ],
                ],
                'programInfo' => (object)[
                    'heading' => 'Interested in getting started?',
                    'paragraphs' => 'Contact us to get more information about the Car Dealership Program.',
                    'button' => (object)[
                        'route' => 'support.contact-us',
                        'text' => 'Request Information',
                        'queryParams' => '?regarding=Programs Inquiry&program=Car Dealership Program&show=email',
                    ],
                ],
            ],
        ]);
    }
}
