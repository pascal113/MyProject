<?php

declare(strict_types=1);

namespace App\Models;

use App\GatewayModel;

class VehicleColor extends GatewayModel
{
    /**
     * Db table
     *
     * @var string
     */
    protected $table = 'vehicle_colors';
}
