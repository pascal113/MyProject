<?php

namespace App\Models;

use App\GatewayModel;
use App\Models\OrderProduct;

class MembershipPurchase extends GatewayModel
{
    /**
     * Define primary key type
     *
     * @var string
     */
    public $keyType = 'string';

    /**
     * Primary key does not increment
     *
     * @var boolean
     */
    public $incrementing = false;

    /**
     * Create a new instance
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->order_product = $this->order_product ? new OrderProduct((array)$this->order_product) : null;
    }

    /**
     * ->certificate_url
     */
    public function getCertificateUrlAttribute(): string
    {
        return url(config('services.gateway.base_url').'/membership-purchases/'.$this->id.'/certificate/pdf');
    }
}
