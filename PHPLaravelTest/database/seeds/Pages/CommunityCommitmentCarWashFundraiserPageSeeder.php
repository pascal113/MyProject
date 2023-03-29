<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class CommunityCommitmentCarWashFundraiserPageSeeder extends Seeder
{
    use FileCopyTrait;

    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/community-commitment-charity.jpg',
            'main_image1' => 'Pages/images/community-commitment/charity-thumbs.jpg',
            'main_image2' => 'Pages/images/community-commitment/unlimited-wash002.jpg',
            'quote_image' => 'Pages/images/quote-avatar-alex.png',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        $parentPage = Page::where('slug', 'community-commitment')->firstOrFail();

        Page::create([
            'parent_page_id' => $parentPage->id,
            'slug' => 'car-wash-fundraiser',
            'category' => 'main',
            'template' => 'community-commitment.car-wash-fundraiser',
            'title' => 'Car Wash Fundraiser',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => 'Partner with Brown Bear for your next fundraiser!',
                    'image' => $destImages['hero'],
                ],
                'intro' => (object)[
                    'heading' => 'Car Wash Fundraiser',
                    'paragraphs' => 'The Brown Bear Car Wash Fundraiser program offers an effective car wash fundraiser for qualifying non-profit organizations. You can sell Car Wash Fundraiser tickets for our Tunnel locations!',
                ],
                'contentBlocks' => [
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::IMAGES_AND_TEXT,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_BLUE,
                        'images' => [
                            $destImages['main_image1'],
                            $destImages['main_image2'],
                        ],
                        'heading' => 'Our car wash fundraiser can work for you!',
                        'wysiwyg' => "Selling Car Wash fundraising tickets instead of having a parking lot car wash fundraiser is not only an easy way for you to raise funds, it’s also better for the environment!\n\n"
                            ."* Tickets are good at any Brown Bear Car Wash Tunnel location.\n"
                            ."* You can sell car wash fundraiser tickets any time of the year.\n"
                            ."* Your car wash fundraiser doesn't have to be limited to a day or weekend, or thwarted by weather.\n"
                            ."* You can raise more money with the same number of people.\n"
                            ."* It gives your organization a fundraising potential of 500% return!\n"
                            ."* Raise funds and protect the environment at the same time!",
                    ],
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::QUOTE,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_WHITE,
                        'attribution' => 'Alex, Seattle',
                        'image' => $destImages['quote_image'],
                        'quote' => 'Over $8,000,000 has been raised by local non-profit organizations through the Brown Bear Car Wash Fundraiser Program! The Bear Cares!',
                    ],
                    (object)[
                        'componentType' => \FPCS\FlexiblePageCms\ContentBlocks::ICON_AND_PARAGRAPH,
                        'wrapperColorClass' => \FPCS\FlexiblePageCms\ContentBlocks::WRAPPER_COLOR_WHITE,
                        'heading' => 'How it Works',
                        'icon' => 'thumbs-up',
                        'introParagraph' => 'The Brown Bear Car Wash Fundraiser program gives your organization everything you need to be successful. Our tickets are sold at a low price, which allows you to re-sell them for a great fundraising rate.',
                        'wysiwyg' => "### Good for One Wash\n\n"
                            ."Each Car Wash Fundraiser fundraiser ticket is good for one Beary Clean car wash.\n\n"
                            ."### Customized Tickets\n\n"
                            ."Each ticket is customized with your Organization's name or message.  Tickets are $1.50 each with a suggested selling price of $9.00 to $11.00 each.\n\n"
                            ."### Sell More with Help\n\n"
                            ."If 20 people in your organization each sell 20 tickets at $9.00 each, you’ll raise $3,000.  It’s that easy!\n\n",
                    ],
                ],
                'interestedInGettingStarted' => (object)[
                    'heading' => 'Interested in getting started?',
                    'wysiwyg' => "Get started with the Charity Car Wash Program with these easy steps:\n\n"
                        ."1. **[Review the Brochure (PDF)](https://s3-us-west-2.amazonaws.com/brown-bear-redesign-production/apps/brown-bear-redesign/releases/20190116232834/public/ckeditor_assets/attachments/112/bb_charity_broch_2019.pdf)**.\n"
                        ."2. Complete both pages of the **[Application (PDF)](https://s3-us-west-2.amazonaws.com/brown-bear-redesign-production/apps/brown-bear-redesign/releases/20190116232834/public/ckeditor_assets/attachments/113/bbcharitywashapplication2019.pdf)**, including a valid non-profit tax ID number.\n"
                        ."3. Mail, fax, email, or hand-deliver the application with payment to our corporate office at: **3977 Leary Way NW, Seattle 98107**. \n\n"
                        ."Please allow 7-10 business days for turnaround time.",
                    'button' => (object)[
                        'route' => 'support.contact-us',
                        'text' => 'Request Information',
                        'queryParams' => '?regarding=Programs Inquiry&program=Charity Car Wash Program&show=email',
                    ],
                ],
            ],
        ]);
    }
}
