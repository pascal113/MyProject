<?php

use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class WashCardPageSeeder extends Seeder
{
    use FileCopyTrait;
    public function run()
    {
        Page::create([
            'slug' => 'wash-card',
            'category' => 'main',
            'template' => 'wash-cards.check-balance',
            'title' => 'Wash Card Balance',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => 'Flexible & Easy Wash Cards',
                    'image' => null,
                ],
                'intro' => (object)[
                    'heading' => 'Brown Bear Wash Cards',
                    'paragraphs' => 'Welcome! Brown Bear Wash Cards can be used at any tunnel wash location.',
                ],
                'iconAndParagraph' => (object)[
                    'heading' => 'Buy Digital Ticket Books today',
                    'icon' => 'market',
                    'paragraphs' => 'Brown Bear Digital Ticket Books are now available on our website. Purchase digital cards for use by yourself, or someone you love. Digital wash cards make a great gift.',
                    'button' => (object)[
                        'route' => 'shop.wash-cards-ticket-books',
                        'text' => 'Browse Digital Wash Cards',
                    ],
                ],
            ],
        ]);
    }
}
