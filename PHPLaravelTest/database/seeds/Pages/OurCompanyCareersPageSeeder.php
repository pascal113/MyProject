<?php

declare(strict_types=1);

use App\Models\Page;
use Illuminate\Database\Seeder;

class OurCompanyCareersPageSeeder extends Seeder
{
    use FileCopyTrait;

    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/careers.jpg',
            'quote_image' => 'Pages/images/careers/quote-avatar-jimmy.png',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        $parentPage = Page::where('slug', 'our-company')->firstOrFail();

        Page::create([
            'parent_page_id' => $parentPage->id,
            'slug' => 'careers',
            'category' => 'main',
            'template' => 'our-company.careers',
            'title' => 'Careers',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => 'We offer a great culture, amazing benefits (and a bit of fun).',
                    'image' => $destImages['hero'],
                ],
                'intro' => (object)[
                    'heading' => 'Careers at Brown Bear',
                    'paragraphs' => "We have provided personal growth and career opportunities for three generations of Puget Sound area residents. In fact, a number of the region's leading business and civic leaders first worked as car washers at Brown Bear Car Wash.",
                ],
                'companyBenefits' => (object)[
                    'heading' => 'Our Culture & Benefits',
                    'videoUrl' => 'https://www.youtube.com/embed/L8b9UGIHjSI',
                    'wysiwyg' => "Our culture is one of respect and discipline. Expectations are clear. Good employees are recognized, rewarded, developed, and given the opportunity to have a job for life. Many of our long term employees started as car washers at the site level and were promoted into positions which have become careers. We expect our employees to work hard and take pride in the work that they do and recognize that \"the Customer is King.\"\n\n"
                        ."* Competitive starting wages\n"
                        ."* Direct deposit\n"
                        ."* Specific holiday pay\n"
                        ."* Commissions and/or bonuses\n"
                        ."* Vacation payout program\n"
                        ."* Medical, Dental and Vision\n"
                        ."* 401K plan\n"
                        ."* Tuition reimbursement\n"
                        ."* Seniority recognition\n"
                        ."* Free car washes\n"
                        ."* Sick & Safe Leave",
                ],
                'quote' => (object)[
                    'image' => $destImages['quote_image'],
                    'quote' => 'I really enjoy working for Brown Bear. The company has a great culture. I am able to maintain a flexible schedule. It has been a very positive experience.',
                    'attribution' => 'Jimmy, Seattle',
                ],
                'employeeSupport' => (object)[
                    'heading' => 'Recognition & Support',
                    'wysiwyg' => "### Guard & Reserve Support\n\n"
                        ."We are committed to employing Guard & Reserve service members at Brown Bear. We are honored to have these dedicated men and women among our ranks\n\n"
                        ."### Diversity & Inclusion\n\n"
                        ."Seattle is home to a diverse community of qualified individuals ready and willing to work. We are committed to making every individual feel at home at Brown Bear.\n\n",
                    'button' => (object)[
                        'route' => 'about-our-washes.index',
                        'text' => 'Our Employment Commitment',
                    ],
                ],
                'openings' => (object)[
                    'heading' => 'Current Openings',
                    'icon' => 'doc',
                    'paragraphs' => 'To view current openings please check our our listings on our HR portal. Thank you for your interest in Brown Bear Car Wash!',
                    'button' => (object)[
                        'route' => 'about-our-washes.index',
                        'text' => 'View Our Current Openings',
                    ],
                ],
            ],
        ]);
    }
}
