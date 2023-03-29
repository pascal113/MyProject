<?php

declare(strict_types=1);

namespace App\Models;

use FPCS\FlexiblePageCms\Models\CmsPage;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Page extends CmsPage
{
    /**
     * Override list of routes available in FPCMS admin interface
     */
    public static function overrideRoutesForFormField(Collection $routes): Collection
    {
        $publicFiles = File::allPublic();

        $publicFilesRoutes = $publicFiles->map(function ($file) {
            return [
                'isCms' => false,
                'route' => 'files.file',
                'routeParams' => [ 'id' => $file->id ],
                'displayName' => 'File: '.$file->name,
            ];
        });

        return $routes->map(function ($route) {
            if ($route->route === 'support.contact-us') {
                $route->queryParams = (object)[
                    'label' => 'Regarding',
                    'type' => 'select',
                    'options' => [
                        [
                            'value' => '?regarding=Corporate Inquiry&show=email',
                            'text' => 'Corporate Inquiry',
                        ],
                        [
                            'value' => '?regarding=Car Wash Location Inquiry&show=email',
                            'text' => 'Car Wash Location Inquiry',
                        ],
                        [
                            'value' => '?regarding=Unlimited Wash Club&show=email',
                            'text' => 'Unlimited Wash Club',
                        ],
                        [
                            'value' => '?regarding=Programs Inquiry&program=Charity Car Wash Program&show=email',
                            'text' => 'Programs Inquiry -> Charity Car Wash Program',
                        ],
                        [
                            'value' => '?regarding=Programs Inquiry&program=Car Dealership Program&show=email',
                            'text' => 'Programs Inquiry -> Car Dealership Program',
                        ],
                        [
                            'value' => '?regarding=Programs Inquiry&program=Fleet Wash Program&show=email',
                            'text' => 'Programs Inquiry -> Fleet Wash Program',
                        ],
                    ],
                ];
            } elseif ($route->route === 'support') {
                $route->anchorTags = (object)[
                    'label' => 'Expanded FAQ section',
                    'type' => 'select',
                    'options' => [],
                ];
                $page = Page::where('slug', 'support')->first();
                $faqArray = (array)$page->content->faq;

                foreach ($faqArray['items'] as $sectionContent) {
                    $route->anchorTags->options[] = [
                        'value' => '#'.Str::slug($sectionContent->heading),
                        'text' => $sectionContent->heading,
                    ];
                }
            } elseif ($route->route === 'locations') {
                $route->anchorTags = (object)[
                    'label' => 'Selected Features',
                    'type' => 'select',
                    'options' => [],
                ];
                $services = Service::all();

                foreach ($services as $service) {
                    $route->anchorTags->options[] = [
                        'value' => '?services=["'.$service->slug.'"]',
                        'text' => $service->name,
                    ];
                }
            }

            return $route;
        })->concat($publicFilesRoutes);
    }
}
