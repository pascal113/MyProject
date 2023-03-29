<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Service;
use FPCS\FlexiblePageCms\Services\CmsRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View as ViewMaker;

class LocationController extends Controller
{
    /**
     * Location page
     */
    public function index(Request $request)
    {
        $page = CmsRoute::getPageByPathOrFail('locations');
        $page->prepareContentForPublicDisplay();

        $place = $request->get('place');
        $radius = $request->get('radius') ?? 5;
        $services = json_decode($request->get('services', ''));

        $locations = Location::with('services')->get()->map(function ($location) {
            $location->map_info_window_html = ViewMaker::make('locations.partials.map-info-window', ['location' => $location])->render();

            return $location->sanitizeForJs();
        });
        $allServices = Service::all();

        return parent::view('flexible-page-cms.templates.locations.index', compact([
            'locations',
            'allServices',
            'place',
            'radius',
            'services',
            'page',
        ]));
    }

    /**
     * Location detail page
     */
    public function show(string $dynamicSlug)
    {
        $id = Location::getIdFromDynamicSlug($dynamicSlug);
        $location = Location::findOrFail($id)->sanitizeForJs();
        $nearbyLocations = $location->getNearbyLocations()->map(function ($location) {
            return $location->sanitizeForJs();
        });
        $location->map_info_window_html = ViewMaker::make('locations.partials.map-info-window', ['location' => $location])->render();
        $canonical_url = $location->url;
        if (!empty($location->canonical_url)) {
            $canonical_url = url(CmsRoute::has($location->canonical_url) ? CmsRoute::get($location->canonical_url) : $location->canonical_url);
        }

        // Generate `department` data for schema.org
        $servicesWithIsoWeekdayHours = $location->services->map(function ($service) {
            $service->isoWeekdayHours = collect(range(1, 7))
                ->reduce(function ($acc, $isoWeekday) use ($service) {
                    $acc[$isoWeekday] = (object)[
                        'opens_at' => (int)$service->pivot->{'day_'.$isoWeekday.'_opens_at'},
                        'closes_at' => (int)$service->pivot->{'day_'.$isoWeekday.'_closes_at'},
                    ];

                    return $acc;
                }, []);

            return $service;
        });
        $departments = $servicesWithIsoWeekdayHours->map(function ($serviceWithIsoWeekdayHours) use ($location) {
            return (object)[
                '@type' => $serviceWithIsoWeekdayHours->schema_type,
                'name' => 'Brown Bear Car Wash '.$serviceWithIsoWeekdayHours->name,
                'telephone' => $location->phone,
                'openingHoursSpecification' => self::getOpeningHoursSpecification($serviceWithIsoWeekdayHours->isoWeekdayHours),
                'image' => [
                    url('/img/logo-og.jpg'),
                ],
                'logo' => url('/img/logo-og.jpg'),
                'address' => (object)[
                    '@type' => 'PostalAddress',
                    'streetAddress' => $location->address_line_1,
                    'addressLocality' => $location->address_line_2,
                ],
                'priceRange' => $serviceWithIsoWeekdayHours->price_range,
            ];
        });

        // Derive longest hours for each day of the week, for schema.org top-level openingHoursSpecification
        $isoWeekdaysLongestHours = collect(range(1, 7))
            ->reduce(function ($acc, $isoWeekday) use ($servicesWithIsoWeekdayHours) {
                $acc[$isoWeekday] = (object)[
                    'opens_at' => $servicesWithIsoWeekdayHours
                        ->map(function ($serviceWithIsoWeekdayHours) use ($isoWeekday) {
                            return $serviceWithIsoWeekdayHours->isoWeekdayHours[$isoWeekday]->opens_at;
                        })
                        ->min(),
                    'closes_at' => $servicesWithIsoWeekdayHours
                        ->map(function ($serviceWithIsoWeekdayHours) use ($isoWeekday) {
                            return $serviceWithIsoWeekdayHours->isoWeekdayHours[$isoWeekday]->closes_at;
                        })
                        ->max(),
                ];

                return $acc;
            }, []);

        // Parse address line 2 into city/state/zip
        if (preg_match('/^([^,]*),[ ]*([a-zA-Z]{2})[ ]*([0-9\-]*)$/', $location->address_line_2, $matches)) {
            $addressLocality = $matches[1];
            $addressRegion = $matches[2];
            $postalCode = $matches[3];
        } else {
            $addressLocality = $addressRegion = $postalCode = $location->address_line_2;
        }

        $meta = parent::meta((object)[
            'title' => $location->meta_title,
            'description' => $location->meta_description,
            'keywords' => $location->meta_keywords,
            'canonical_url' => $canonical_url,
            'schemaOrg' => [
                '@context' => 'https://schema.org',
                '@type' => 'AutoWash',
                'url' => url()->current(),
                'name' => $location->title,
                'telephone' => $location->phone,
                'address' => (object)[
                    '@type' => 'PostalAddress',
                    'streetAddress' => $location->address_line_1,
                    'addressLocality' => $addressLocality,
                    'addressRegion' => $addressRegion,
                    'postalCode' => $postalCode,
                    'addressCountry' => 'US',
                ],
                'priceRange' => $location->price_range,
                'openingHoursSpecification' => self::getOpeningHoursSpecification($isoWeekdaysLongestHours),
                'department' => $departments,
            ],
        ]);

        if ($location->meta_same_as ?? null) {
            $meta->schemaOrg['sameAs'] = $location->meta_same_as;
        }

        return parent::view('locations.show', compact('location', 'nearbyLocations', 'meta'));
    }

    /**
     * Return an array of valid schema.org OpeningHoursSpecification data
     * Days will be grouped into days with the same hours
     */
    private static function getOpeningHoursSpecification(array $isoWeekdayHours): array
    {
        $groupsOfWeekdaysWithSameHours = collect(range(1, 7))
            ->reduce(function ($acc, $isoWeekday) use ($isoWeekdayHours) {
                $key = $acc->keys()->first(function ($key) use ($acc, $isoWeekdayHours, $isoWeekday) {
                    return $acc[$key]->opens_at === $isoWeekdayHours[$isoWeekday]->opens_at && $acc[$key]->closes_at === $isoWeekdayHours[$isoWeekday]->closes_at;
                });

                $isoWeekdayWithSameHours = $acc[$key] ?? (object)[
                    'opens_at' => $isoWeekdayHours[$isoWeekday]->opens_at,
                    'closes_at' => $isoWeekdayHours[$isoWeekday]->closes_at,
                    'isoWeekdays' => [],
                ];
                $isoWeekdayWithSameHours->isoWeekdays[] = $isoWeekday;

                $acc[$key] = $isoWeekdayWithSameHours;

                return $acc;
            }, collect([]));

        return $groupsOfWeekdaysWithSameHours->map(function ($weekdaysWithSameHours) {
            return [
                '@type' => 'OpeningHoursSpecification',
                'opens' => Carbon::parse($weekdaysWithSameHours->opens_at)->format('H:i'),
                'closes' => ($weekdaysWithSameHours->opens_at === 0 && $weekdaysWithSameHours->closes_at === 86400) ? '23:59' : Carbon::parse($weekdaysWithSameHours->closes_at)->format('H:i'),
                'dayOfWeek' => collect($weekdaysWithSameHours->isoWeekdays)->map(function ($isoWeekday) {
                    return ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'][$isoWeekday - 1];
                })->toArray(),
            ];
        })->toArray();
    }
}
