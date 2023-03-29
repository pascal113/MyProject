<?php

declare(strict_types=1);

use App\Models\Page;
use Illuminate\Database\Seeder;

class OurCompanyLeadershipPageSeeder extends Seeder
{
    use FileCopyTrait;
    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/leadership.jpg',
            'people_image1' => 'Pages/images/our-company/leadership/team001.png',
            'people_image2' => 'Pages/images/our-company/leadership/team002.png',
            'people_image3' => 'Pages/images/our-company/leadership/team003.png',
            'people_image4' => 'Pages/images/our-company/leadership/team004.png',
            'people_image5' => 'Pages/images/our-company/leadership/team005.png',
            'people_image6' => 'Pages/images/our-company/leadership/team007.png',
            'people_image7' => 'Pages/images/our-company/leadership/team008.png',
            'people_image8' => 'Pages/images/our-company/leadership/team009.png',
            'people_image9' => 'Pages/images/our-company/leadership/team006.png',
            'placeholder_image' => 'Pages/images/our-company/leadership/team-placeholder.png',

        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        $parentPage = Page::where('slug', 'our-company')->firstOrFail();

        Page::create([
            'parent_page_id' => $parentPage->id,
            'slug' => 'leadership',
            'category' => 'main',
            'template' => 'our-company.leadership',
            'title' => 'Meet Our Team',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => 'Our team and culture are second to none.',
                    'image' => $destImages['hero'],
                ],
                'intro' => (object)[
                    'heading' => 'Meet Our Team',
                    'paragraphs' => 'Our company has some of the smartest and most capable leaders in the industry working right here in Seattle.',
                ],
                'corporateExecutivesSection' => (object)[
                    'heading' => 'Corporate Executives',
                    'paragraphs' => 'Most of our corporate executives have been a part of the team throughout most of their career, making them the heart and soul of Brown Bear.',
                    'people' => [
                        (object)[
                            'photo' => $destImages['people_image1'],
                            'name' => 'Victor Odermat',
                            'jobTitle' => 'President',
                            'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                        ],
                        (object)[
                            'photo' => $destImages['people_image2'],
                            'name' => 'Lance Odermat',
                            'jobTitle' => 'Vice President / General Counsel',
                            'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                        ],
                        (object)[
                            'photo' => $destImages['people_image3'],
                            'name' => 'Tom North',
                            'jobTitle' => 'Chief Operations Officer',
                            'bio' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.',
                        ],
                        (object)[
                            'photo' => $destImages['people_image4'],
                            'name' => 'Steve Palmer',
                            'jobTitle' => 'Chief Financial Officer / Marketing Director',
                            'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                        ],
                    ],
                ],
                'seniorManagersSection' => (object)[
                    'heading' => 'Senior Managers',
                    'paragraphs' => 'Brown Bear Car Wash is lead by a talented and experienced team of executives and managers. Our number one mission is to provide the best car wash services available anywhere while also protecting our water resources.',
                    'people' => [
                        (object)[
                            'photo' => $destImages['people_image9'],
                            'name' => 'Omar Molinari',
                            'jobTitle' => 'General Manager, Tunnel Operations',
                            'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                        ],
                        (object)[
                            'photo' => $destImages['people_image5'],
                            'name' => 'Jeff Knutson',
                            'jobTitle' => 'Self-Serve Operations Manager',
                            'bio' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.',
                        ],
                        (object)[
                            'photo' => $destImages['people_image6'],
                            'name' => 'George Hobson',
                            'jobTitle' => 'Human Resources Manager',
                            'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                        ],
                        (object)[
                            'photo' => $destImages['people_image7'],
                            'name' => 'Mark Jacobsen',
                            'jobTitle' => 'Service Superviser',
                            'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                        ],
                        (object)[
                            'photo' => $destImages['people_image8'],
                            'name' => 'Darren Bruno',
                            'jobTitle' => 'Electronics Supervisor',
                            'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                        ],
                    ],
                ],
                'areaManagersSection' => (object)[
                    'heading' => 'Area Managers',
                    'paragraphs' => 'Brown Bear Car Wash has earned a reputation for delivering great customer service. Each of our Area Managers is responsible for ensuring that every Brown Bear Car Wash location delivers on the promise of "Wash Green. Cruise Clean.®"',
                    'people' => [
                        (object)[
                            'photo' => $destImages['placeholder_image'],
                            'name' => 'Team Member',
                            'jobTitle' => 'Area Manager',
                            'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                        ],
                        (object)[
                            'photo' => $destImages['placeholder_image'],
                            'name' => 'Team Member',
                            'jobTitle' => 'Area Manager',
                            'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                        ],
                    ],
                ],

                'joinOurTeam' => (object)[
                    'heading' => 'Join Our Team!',
                    'paragraphs' => 'We have provided personal growth and career opportunities for three generations of Puget Sound area residents. In fact, a number of the region\'s leading business and civic leaders first worked as car washers at Brown Bear Car Wash to help pay college expenses, and later carried our philosophy of uncompromising personal service and the pursuit of excellence into other walks of life.',
                    'videoUrl' => 'https://www.youtube.com/embed/L8b9UGIHjSI',
                    'button' => (object)[
                        'route' => 'our-company.careers',
                        'text' => 'Read About Careers',
                    ],
                ],
            ],
        ]);
    }
}
