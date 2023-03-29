<?php

declare(strict_types=1);

namespace App\Models;

use App\Model;
use App\Traits\Spatial;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Location extends Model
{
    use SoftDeletes;
    use Spatial;

    /**
     * Mass-assignable attributes
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'hero_image',
        'site_number',
        'lng_lat',
        'address_line_1',
        'address_line_2',
        'phone',
        'manager_name',
        'manager_title',
        'price_range',
        'temporarily_closed',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_same_as',
        'canonical_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'meta_same_as' => 'array',
    ];

    /**
     * Spatial attributes
     *
     * @var array
     */
    protected $spatial = ['lng_lat'];

    /**
     * Default radius for filter - 5 miles
     *
     * @var int
     */
    public const DEFAULT_MILES_RADIUS = 5.0;

    public const MILES_TO_METERS_RATIO = 1609.34;

    /**
     * Get services
     */
    public function services()
    {
        $pivots = ['price_range'];
        for ($i = 1; $i <= 7; $i++) {
            $pivots[] = 'day_'.$i.'_opens_at';
            $pivots[] = 'day_'.$i.'_closes_at';
        }

        return $this
            ->belongsToMany(Service::class, 'location_service')
            ->withPivot($pivots);
    }

    /**
     * Auto-format phone number to XXX.XXX.XXXX
     */
    public function setPhoneAttribute($value): void
    {
        $formattedValue = $value;
        $formattedValue = preg_replace('/[^0-9]/', '', $formattedValue);
        $formattedValue = preg_replace('/^([0-9]{3})([0-9]{3})([0-9]{4})/', '$1.$2.$3', $formattedValue);

        $this->attributes['phone'] = $formattedValue;
    }

    /**
     * Generate dynamic slug
     */
    public function getDynamicSlugAttribute()
    {
        return Str::slug($this->address_line_1.' '.$this->address_line_2.' '.$this->id);
    }

    /**
     * Generate URL
     */
    public function getUrlAttribute()
    {
        return route('locations.show', [ $this->dynamic_slug ]);
    }

    /**
     * Return price_range, defaulted
     */
    public function getPriceRangeAttribute(): string
    {
        return $this->attributes['price_range'] ?? null ? (string)$this->attributes['price_range'] : '$10-16';
    }

    /**
     * Sanitize locations data so that it is safe to inject into Vue/javascript
     */
    public function sanitizeForJs(): Location
    {
        $this->lat = $this->getCoordinates()[0]['lat'];
        $this->lng = $this->getCoordinates()[0]['lng'];
        unset($this->lng_lat);
        $this->url = $this->url;

        $this->services = $this->services->map(function ($service) {
            $service->isOpen = $service->isOpen;

            return $service;
        });

        return $this;
    }

    /**
     * Method to include locations within a given radius (meters).
     */
    public static function filterByMilesRadius(float $lat, float $lng, ?float $milesRadius): Builder
    {
        $milesRadius = (float)$milesRadius ?? self::DEFAULT_MILES_RADIUS;

        if (DB::getDriverName() === 'sqlite') {
            /**
             * This is a workaround for test that run using SQLite.
             * SQLite does not support spatial functions or advanced math functions, so we must do it in PHP.
             */

            $allLocations = self::select('id', 'lng_lat')->get();

            $locationsWithinRadius = $allLocations->filter(function ($location) use ($lat, $lng, $milesRadius) {
                list($locationLng, $locationLat) = self::extractLngLatFromSqlLiteString($location->lng_lat);

                $distance = self::vincentyGreatCircleDistance($lat, $lng, $locationLat, $locationLng);

                return $distance <= $milesRadius;
            });

            return static::whereIn('id', $locationsWithinRadius->pluck('id')->toArray());
        }

        return static::select(DB::raw('*, ( 3959 * acos( cos( radians('.$lat.') ) * cos( radians(ST_Y(lng_lat)) ) * cos( radians(ST_X(lng_lat)) - radians('.$lng.') ) + sin( radians('.$lat.') ) * sin(radians(ST_Y(lng_lat))) ) ) AS miles_distance'))
            ->havingRaw('miles_distance <= ?', [$milesRadius])
            ->orderBy('miles_distance');
    }

    /*
     * Method to get other nearby locations within a given radius.
     */
    public function getNearbyLocations(int $minMilesRadius = 5, int $maxMilesRadius = 20): Collection
    {
        $milesRadius = $minMilesRadius;
        $results = collect([]);

        while (!$results->count() && $milesRadius <= $maxMilesRadius) {
            $query = static::filterByMilesRadius(floatval($this->lat), floatval($this->lng), $milesRadius);

            $results = $query->where('id', '<>', $this->id)->limit(5)->get();

            $milesRadius += 5;
        }

        return $results;
    }

    /**
     * Extracts id of the location at the end of the dynamic slug string
     */
    public static function getIdFromDynamicSlug(string $dynamicSlug): ?int
    {
        $regExp = '/^.*-([0-9]*)$/';

        if (!preg_match($regExp, $dynamicSlug)) {
            return null;
        }

        if (!$id = (int)preg_replace($regExp, '$1', $dynamicSlug)) {
            return null;
        }

        return $id;
    }
}
