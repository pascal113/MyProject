<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    /**
     * Fixed-point conversion: Convert cents to dollars
     */
    protected static function toFixedPoint(int $value, int $factor = 100): float
    {
        return ($value ?? 0) / $factor;
    }

    /**
     * Fixed-point conversion: Convert dollars to cents
     */
    protected static function fromFixedPoint(float $value, int $factor = 100): int
    {
        $multipliedByFactor = filter_var(((float)$value ?? 0) * $factor, FILTER_SANITIZE_NUMBER_INT);

        return (int)$multipliedByFactor;
    }
}
