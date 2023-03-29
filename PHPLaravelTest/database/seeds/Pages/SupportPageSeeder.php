<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class SupportPageSeeder extends Seeder
{
    use FileCopyTrait;

    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/support-self-wash.jpg',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        Page::create([
            'slug' => 'support',
            'category' => 'main',
            'template' => 'support.index',
            'title' => 'Support',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => 'Customer service is our highest priority.',
                    'image' => $destImages['hero'],
                ],
                'intro' => (object)[
                    'heading' => 'Customer Support',
                    'paragraphs' => 'At Brown Bear Car Wash we put customer service first. Please take a look at our Frequently Asked Questions (FAQs) for answers. If you don’t find what you’re looking for please reach out. We’re happy to help.',
                ],
                'faq' => (object)[
                    'heading' => 'Frequently Asked Questions',
                    'paragraphs' => 'With over 60 locations around Puget Sound we get a fair number of questions. Please take a look at our FAQs for quick information on Brown Bear products, services and programs.',
                    'items' => [
                        (object)[
                            'heading' => 'About Our Washes',
                            'wysiwyg' => "### What is the best car wash value?\n\n"
                            ."We would recommend that you view information in the About Our Washes area to learn about our car wash offerings. In terms of price, our Self-Serve washes are the best value.\n\n"
                            ."### What is the best car wash value?\n\n"
                            ."Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse volutpat, tellus id semper ullamcorper, nisl ante interdum ex, sit amet iaculis augue massa non urna.\n\n",
                        ],
                        (object)[
                            'heading' => 'Car Wash Related Questions',
                            'wysiwyg' => "### What is the best car wash value?\n\n"
                                ."We would recommend that you view information in the About Our Washes area to learn about our car wash offerings. In terms of price, our Self-Serve washes are the best value.\n\n"
                                ."### What is the best car wash value?\n\n"
                                ."Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse volutpat, tellus id semper ullamcorper, nisl ante interdum ex, sit amet iaculis augue massa non urna.\n\n",
                        ],
                        (object)[
                            'heading' => 'Rewards Program Question',
                            'wysiwyg' => "### What is the best car wash value?\n\n"
                                ."We would recommend that you view information in the About Our Washes area to learn about our car wash offerings. In terms of price, our Self-Serve washes are the best value.\n\n"
                                ."### What is the best car wash value?\n\n"
                                ."Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse volutpat, tellus id semper ullamcorper, nisl ante interdum ex, sit amet iaculis augue massa non urna.\n\n",
                        ],
                        (object)[
                            'heading' => 'Website Questions',
                            'wysiwyg' => "### What is the best car wash value?\n\n"
                                ."We would recommend that you view information in the About Our Washes area to learn about our car wash offerings. In terms of price, our Self-Serve washes are the best value.\n\n"
                                ."### What is the best car wash value?\n\n"
                                ."Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse volutpat, tellus id semper ullamcorper, nisl ante interdum ex, sit amet iaculis augue massa non urna.\n\n",
                        ],
                        (object)[
                            'heading' => 'Rewards App Questions',
                            'wysiwyg' => "### What is the best car wash value?\n\n"
                                ."We would recommend that you view information in the About Our Washes area to learn about our car wash offerings. In terms of price, our Self-Serve washes are the best value.\n\n"
                                ."### What is the best car wash value?\n\n"
                                ."Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse volutpat, tellus id semper ullamcorper, nisl ante interdum ex, sit amet iaculis augue massa non urna.\n\n",
                        ],
                        (object)[
                            'heading' => 'Careers, Recriting & HR',
                            'wysiwyg' => "### What is the best car wash value?\n\n"
                                ."We would recommend that you view information in the About Our Washes area to learn about our car wash offerings. In terms of price, our Self-Serve washes are the best value.\n\n"
                                ."### What is the best car wash value?\n\n"
                                ."Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse volutpat, tellus id semper ullamcorper, nisl ante interdum ex, sit amet iaculis augue massa non urna.\n\n",
                        ],
                        (object)[
                            'heading' => 'Press & Media',
                            'wysiwyg' => "### What is the best car wash value?\n\n"
                                ."We would recommend that you view information in the About Our Washes area to learn about our car wash offerings. In terms of price, our Self-Serve washes are the best value.\n\n"
                                ."### What is the best car wash value?\n\n"
                                ."Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse volutpat, tellus id semper ullamcorper, nisl ante interdum ex, sit amet iaculis augue massa non urna.\n\n",
                        ],
                        (object)[
                            'heading' => 'Other',
                            'wysiwyg' => "### What is the best car wash value?\n\n"
                                ."We would recommend that you view information in the About Our Washes area to learn about our car wash offerings. In terms of price, our Self-Serve washes are the best value.\n\n"
                                ."### What is the best car wash value?\n\n"
                                ."Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse volutpat, tellus id semper ullamcorper, nisl ante interdum ex, sit amet iaculis augue massa non urna.\n\n",
                        ],

                    ],
                ],
                'contactUs' => (object)[
                    'icon' => 'contact',
                    'heading' => 'Contact Us?',
                    'paragraphs' => 'If you have questions about Brown Bear, feel free to reach out, and a team member will get in touch. We appreciate your interest in our company.',
                    'button' => (object)[
                        'route' => 'support.contact-us',
                        'text' => 'View Contact Information',
                    ],
                ],
            ],
        ]);
    }
}
