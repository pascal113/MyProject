<?php

declare(strict_types=1);

namespace App\Models;

use App\Model;
use App\Models\Location;

class Service extends Model
{
    /**
     * Mass-assignable attributes
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'icon_class',
        'schema_type',
    ];

    /**
     * Is this Service open right now?
     * This must be an instance that was pivoted off of a Location, otherwise will return null
     */
    public function getIsOpenAttribute(): bool
    {
        if ($location = Location::find($this->pivot->location_id)) {
            if ($location->temporarily_closed) {
                return false;
            }
        }

        $now = \Carbon\Carbon::now();

        return $this->isOpenAt($now->isoWeekday(), $now->secondsSinceMidnight());
    }

    /**
     * Is this Service open 24/7?
     * This must be an instance that was pivoted off of a Location, otherwise will return null
     */
    public function getIs247Attribute(): bool
    {
        for ($isoWeekday = 1; $isoWeekday <= 7; $isoWeekday++) {
            if ($this->pivot->{'day_'.$isoWeekday.'_opens_at'} > 0 || $this->pivot->{'day_'.$isoWeekday.'_closes_at'} < 86400) {
                return false;
            }
        }

        return true;
    }

    /**
     * Return price_range, defaulted
     */
    public function getPriceRangeAttribute(): string
    {
        if (!empty($this->pivot->price_range)) {
            return $this->pivot->price_range;
        }

        if ($location = !empty($this->pivot->location_id) ? Location::find($this->pivot->location_id) : null) {
            return $location->price_range;
        }

        return '$10-16';
    }

    /**
     * Return whether this Service is open at the given weekday-of-the-week and time-of-day
     * This must be an instance that was pivoted off of a Location, otherwise will return null
     */
    public function isOpenAt(int $isoWeekday, int $secondsSinceMidnight): bool
    {
        if (!$this->pivot) {
            return null;
        }

        $opensAt = $this->pivot->{'day_'.$isoWeekday.'_opens_at'};
        $closesAt = $this->pivot->{'day_'.$isoWeekday.'_closes_at'};

        if ($closesAt === 0) {
            // Make midnight represent "tonight" instead of "this morning"
            $closesAt = 60 * 60 * 24;
        }

        return $opensAt <= $secondsSinceMidnight && $secondsSinceMidnight < $closesAt;
    }
}
