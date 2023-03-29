<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use TCG\Voyager\Traits\Spatial as BaseSpatial;

trait Spatial
{
    use BaseSpatial {
      getLocation as baseGetLocation;
    }

    /**
     * Get location as WKT from Geometry for given field.
     *
     * @param string $column
     *
     * @return string
     */
    public function getLocation($column)
    {
        if (DB::getDriverName() === 'sqlite') {
            $model = self::select($column)
                ->where('id', $this->id)
                ->first();

            return isset($model) ? $model->$column : '';
        }

        return self::baseGetLocation($column);
    }

    /**
     * Extract Lng and Lat from data as string, used for SQLite support.
     */
    protected static function extractLngLatFromSqlLiteString(string $lngLat): array
    {
        $regex = '/^POINT\(([^ ]*) ([^)]*)\)/';

        $lng = preg_replace($regex, '$1', $lngLat);
        $lat = preg_replace($regex, '$2', $lngLat);

        return [
            $lng,
            $lat,
        ];
    }

    /**
     * Calculates the great-circle distance between two points, with the Vincenty formula.
     * https://stackoverflow.com/a/10054282/1615077
     */
    protected static function vincentyGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 3959): float
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) + pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);

        return $angle * $earthRadius;
    }
}
