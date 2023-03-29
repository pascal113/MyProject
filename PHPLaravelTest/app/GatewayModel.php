<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model as BaseModel;

class GatewayModel extends BaseModel
{
    /**
     * Use specific database
     *
     * @var string
     */
    protected $connection = 'gateway';
}
