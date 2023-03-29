<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class AddSiteManagersToLeadershipPage extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $page = Page::where('template', 'our-company.leadership')->firstOrFail();

        $content = $page->content;

        $content->siteManagersSection = (object)[
            'heading' => 'Site Managers',
            'paragraphs' => 'Each of our locations is managed by one of our dedicated site managers.',
            'people' => [
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'Christina Berg',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'Jay Boisen',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'Scott Butcher',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'Luis Carbajal',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'Hannah Chadha',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'Jorge Cuevas-Sanchez',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'Beth Cunningham',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'Donnie Davis',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'Veronica Fierro-Anaya',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'Justin Fischer',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'Kaylin Fontana',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'Gabe Gandara',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'Trish Guinn',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'Hussain Kapasi',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'Chase Lacy',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'Shawn Learned',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'Sheilah Lucas',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'Marcus Meador',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'Eleazer Nakhumwa',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'Stanley Parker',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'Steven Prak',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'Jessica Pritchett',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'Jackie Seifert',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'J.D. Shin',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'Analisa Sierra',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'Robert Trevino',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'Daniel Valencia',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'Forest Walcoff',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'Robin Westcott',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
                (object)[
                    'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.\n
                    \n
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat ante mauris, nec accumsan arcu mattis eget. Pellentesque neque mi, hendrerit non semper quis, egestas in ante.",
                    'name' => 'Antonia Ibarra Serrano',
                    'photo' => '/storage/Pages/images/our-company/leadership/team-placeholder.png',
                    'jobTitle' => 'Site Manager',
                ],
            ],
        ];

        $page->content = $content;

        $page->save();
    }
}
