<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class OverviewsPagesUpdateSeeder extends Seeder
{
    use FileCopyTrait;
    public function run()
    {
        $pages = [
            [
                'slug' => 'shop',
                'contentSections' => [
                    'allProducts' => 'wrapper--blue',
                    'unlimitedWashClubMemberships' => 'wrapper--white',
                    'pawPacksAndTicketBooks' => 'wrapper--blue',
                    'brandedMerchandise' => 'wrapper--white',
                ],
            ],
            [
                'slug' => 'community-commitment',
                'contentSections' => [
                    'carWashFundraiser' => 'wrapper--blue',
                    'charitableDonations' => 'wrapper--white',
                    'washGreen' => 'wrapper--blue',
                    'employmentCommitment' => 'wrapper--white',
                    'diversityAndInclusion' => 'wrapper--blue',
                ],
            ],
            [
                'slug' => 'commercial-programs',
                'contentSections' => [
                    'forHireUnlimitedWashClub' => 'wrapper--blue',
                    'carDealershipProgram' => 'wrapper--white',
                    'fleetWashProgram' => 'wrapper--blue',
                ],
            ],
            [
                'slug' => 'about-our-washes',
                'contentSections' => [
                    'tunnelWash' => 'wrapper--blue',
                    'selfServeWash' => 'wrapper--white',
                    'touchlessWash' => 'wrapper--blue',
                    'topTierGas' => 'wrapper--white',
                    'hungryBearMarket' => 'wrapper--blue',
                ],
            ],
            [
                'slug' => 'wash-clubs',
                'contentSections' => [
                    'unlimitedWashClub' => 'wrapper--blue',
                    'forHireUnlimitedWashClub' => 'wrapper--white',
                ],
            ],
        ];
        foreach ($pages as $page) {
            $foundPage = Page::where('slug', $page['slug'])->firstOrFail();
            $overviewItems = [];
            $pageContent = $foundPage->content;
            foreach ($page['contentSections'] as $sectionKey => $section) {
                $overviewItemWrapperColorClass = $section;
                $content = $pageContent->{$sectionKey};
                $newContent = (object)[
                    'wrapperColorClass' => $overviewItemWrapperColorClass,
                    'heading' => $content->heading,
                    'icon' => $content->icon ?? '',
                    'image' => $content->image,
                    'paragraphs' => $content->paragraphs,
                    'button' => (object)[
                        'route' => $content->button->route,
                        'text' => $content->button->text,
                    ],
                ];
                unset($pageContent->{$sectionKey});
                $overviewItems[] = $newContent;
            }

            $content = (array)$pageContent;
            $content['overviews'] = (object)['items' => $overviewItems];
            $foundPage->content = (object)$content;
            $foundPage->template = 'overview';
            $foundPage->update();
        }
    }
}
