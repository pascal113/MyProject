<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class CommunityCommitmentPageSeeder extends Seeder
{
    use FileCopyTrait;
    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/puget-sound.jpg',
            'fundraiser_image' => 'Pages/images/community-commitment/charity.jpg',
            'donation_image' => 'Pages/images/community-commitment/woodland-zoo.jpg',
            'quote_image' => 'Pages/images/quote-avatar-mallory.png',
            'wash_green_image' => 'Pages/images/community-commitment/wash-green.jpg',
            'commitment_image' => 'Pages/images/community-commitment/reserve-gaurd.jpg',
            'diversity_image' => 'Pages/images/community-commitment/diversity.jpg',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        Page::create([
            'slug' => 'community-commitment',
            'category' => 'main',
            'template' => 'community-commitment.index',
            'title' => 'Community Commitment',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => 'A proud member of the Puget Sound community since 1957',
                    'image' => $destImages['hero'],
                ],
                'intro' => (object)[
                    'heading' => 'Community Commitment',
                    'paragraphs' => 'Brown Bear is proud to be a member of the Puget Sound Community. We pride ourselves on having a positive impact.',
                ],
                'carWashFundraiser' => (object)[
                    'icon' => 'hands-heart',
                    'image' => $destImages['fundraiser_image'],
                    'heading' => 'Car Wash Fundraiser',
                    'paragraphs' => 'The Brown Bear Charity Car Wash Fundraiser offers an effective car wash fundraiser for qualifying non-profit organizations. You can sell Charity Car Wash tickets for our Tunnel locations!',
                    'button' => (object)[
                        'route' => 'community-commitment.car-wash-fundraiser',
                        'text' => 'Car Wash Fundraiser Details',
                    ],
                ],
                'charitableDonations' => (object)[
                    'image' => $destImages['donation_image'],
                    'heading' => 'Charitable Donations',
                    'paragraphs' => 'Brown Bear Car Wash is proud to support charitable organizations that make a difference in our community.',
                    'button' => (object)[
                        'route' => 'community-commitment.giving-back',
                        'text' => 'Charitable Donation Details',
                    ],
                ],
                'quote' => (object)[
                    'image' => $destImages['quote_image'],
                    'quote' => 'Brown Bear has a positive social impact on our community. Their work with the environment and local charitable organizations is commendable and really makes a difference. I love Brown Bear!',
                    'attribution' => 'Mallory, Seattle',
                ],
                'washGreen' => (object)[
                    'icon' => 'cloudy',
                    'image' => $destImages['wash_green_image'],
                    'heading' => 'Wash Green',
                    'paragraphs' => 'Not only do we take great care of your car, but we also strive to take great care of the environment. Learn more about why Brown Bear is so green and how washing with us can be good for salmon.',
                    'button' => (object)[
                        'route' => 'community-commitment.wash-green',
                        'text' => 'Wash Green Details',
                    ],
                ],
                'employmentCommitment' => (object)[
                    'icon' => 'people-group',
                    'image' => $destImages['commitment_image'],
                    'heading' => 'Guard & Reserve Support',
                    'paragraphs' => "We proudly support Employer Support of the Guard and Reserve (ESGR). ESGR, was established in 1972 to promote cooperation between members and their employers and to assist in the resolution of conflicts arising from an employee's military commitment.",
                    'button' => (object)[
                        'route' => 'community-commitment.guard-reserves',
                        'text' => 'Guard & Reserve Support',
                    ],
                ],
                'diversityAndInclusion' => (object)[
                    'image' => $destImages['diversity_image'],
                    'heading' => 'Diversity & Inclusion',
                    'paragraphs' => 'Car Wash Enterprises, Inc., the parent company of Brown Bear Car Wash, believes that every employee has the right to work in surroundings that are free from all forms of unlawful discrimination.',
                    'button' => (object)[
                        'route' => 'community-commitment.diversity-inclusion',
                        'text' => 'Diversity & Inclusion Details',
                    ],
                ],
            ],
        ]);
    }
}
