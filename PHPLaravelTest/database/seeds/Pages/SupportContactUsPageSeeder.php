<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class SupportContactUsPageSeeder extends Seeder
{
    use FileCopyTrait;

    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/support-exterior.jpg',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        $parentPage = Page::where('slug', 'support')->firstOrFail();

        Page::create([
            'parent_page_id' => $parentPage->id,
            'slug' => 'contact-us',
            'category' => 'main',
            'template' => 'support.contact-us',
            'title' => 'Contact Us',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => 'We’re here to help!',
                    'image' => $destImages['hero'],
                ],
                'intro' => (object)[
                    'heading' => 'Contact Us',
                    'paragraphs' => 'We’re here to help. How would you prefer to reach us?',
                ],
                'phone' => (object)[
                    'heading' => 'Phone',
                    'icon' => 'phone',
                    'items' => [
                        (object)[
                            'heading' => 'Main Line',
                            'phoneNumber' => '206.789.3700',
                            'wysiwyg' => "* Technical Support\n"
                            ."* Press Inquiries\n"
                            ."* Careers, Recruiting & HR\n"
                            ."* General Requests",
                        ],
                        (object)[
                            'heading' => 'Unlimited Wash Club',
                            'phoneNumber' => '206.774.3737',
                            'wysiwyg' => "* Membership Questions\n"
                                ."* Start or Stop Your Plan\n",
                        ],
                        (object)[
                            'heading' => 'Charity Car Wash Program',
                            'phoneNumber' => '206.774.3742',
                            'wysiwyg' => "* Start a Charity Wash Program or Check on Your Program’s Status\n",
                        ],
                        (object)[
                            'heading' => 'Car Dealership Program',
                            'phoneNumber' => '206.774.3740',
                            'wysiwyg' => "* Start a Car Dealership Program or Check on Your Program’s Status\n",
                        ],
                    ],
                ],
                'snailMail' => (object)[
                    'icon' => 'snail-mail',
                    'heading' => 'Snail Mail',
                    'paragraphs' => "Car Wash Enterprises, Inc. DBA Brown Bear Car Wash\n\n"
                    ."3977 Leary Way NW Seattle WA 98107-5041",
                ],
                'mailingList' => (object)[
                    'icon' => 'mail',
                    'heading' => 'Mailing List',
                    'paragraphs' => "To receive the latest news, promotions and discounts from Brown Bear Car Wash, please enter your information below.",
                ],
            ],
        ]);
    }
}
