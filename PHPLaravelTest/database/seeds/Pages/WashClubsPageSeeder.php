<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class WashClubsPageSeeder extends Seeder
{
    use FileCopyTrait;
    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/wash-clubs.jpg',
            'unlimited_image' => 'Pages/images/wash-clubs/unlimited-wash-club.jpg',
            'hire_image' => 'Pages/images/wash-clubs/for-hire-unlimited-wash-club.jpg',
            'quote_image' => 'Pages/images/careers/quote-avatar-jimmy.png',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        Page::create([
            'slug' => 'wash-clubs',
            'category' => 'main',
            'template' => 'wash-clubs.index',
            'title' => 'Wash Clubs',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => 'All the best of Brown Bear!',
                    'image' => $destImages['hero'],
                ],
                'intro' => (object)[
                    'heading' => 'Wash Clubs',
                    'paragraphs' => 'From keeping your car clean 24/7, to showing the world your Brown Bear love, we have it all!',
                ],
                'unlimitedWashClub' => (object)[
                    'image' => $destImages['unlimited_image'],
                    'heading' => 'Unlimited Wash Club',
                    'paragraphs' => 'If you love having a clean car all the time, the Brown Bear Unlimited Wash Club is for you. With this simple membership you will receive access to unlimited washes at any of our Puget Sound tunnel wash locations.',
                    'button' => (object)[
                        'route' => 'wash-clubs.unlimited-wash-club',
                        'text' => 'Unlimited Wash Club Details',
                    ],
                ],
                'forHireUnlimitedWashClub' => (object)[
                    'image' => $destImages['hire_image'],
                    'heading' => 'For Hire Unlimited Wash Club',
                    'paragraphs' => 'If you drive a Lyft, Uber or Taxi service around Puget Sound, you know how important it is to keep your vehicle clean and in good shape. Our For Hire Unlimited Wash Club makes maintenance a breeze.',
                    'button' => (object)[
                        'route' => 'wash-clubs.for-hire-unlimited-wash-club',
                        'text' => 'For Hire Unlimited Wash Club Details',
                    ],
                ],
                'quote' => (object)[
                    'image' => $destImages['quote_image'],
                    'quote' => 'Brown Bear is an awesome Seattle company and an iconic brand. I love showing off by Brown Bear pride with fun merchandise.',
                    'attribution' => 'Jimmy, Seattle',
                ],
            ],
        ]);
    }
}
