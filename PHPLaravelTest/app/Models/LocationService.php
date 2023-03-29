<?php

namespace App\Models;

use App\Model;

class LocationService extends Model
{
    /**
     * Mass-assignable attributes
     *
     * day_X values are day-of-the-week; 1=Monday, 7=Sunday
     *
     * opens_at and closes_at values are hours converted to seconds since midnight
     *  Example:
     *  8am => 28800 (8 * 60 * 60)
     *  7pm or 19h => 68400 (19 * 60 * 60)
     *  To indicate "open 24 hours": set opens_at to 0 and closes_at to 86400
     *
     * @var array
     */
    protected $fillable = [
        'location_id',
        'service_id',
        'day_1_opens_at',
        'day_1_closes_at',
        'day_2_opens_at',
        'day_2_closes_at',
        'day_3_opens_at',
        'day_3_closes_at',
        'day_4_opens_at',
        'day_4_closes_at',
        'day_5_opens_at',
        'day_5_closes_at',
        'day_6_opens_at',
        'day_6_closes_at',
        'day_7_opens_at',
        'day_7_closes_at',
        'price_range',
    ];

    /**
     * Turn off automatic timestamp columns
     *
     * @var boolean
     */
    public $timestamps = false;
}
