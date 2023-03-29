<?php

namespace App\Models;

use App\GatewayModel;
use App\Models\OrderProduct;
use App\Services\GatewayService;
use GuzzleHttp\Exception\GuzzleException;

class MembershipModification extends GatewayModel
{
    /**
     * Allow mass-assignment of all attributes
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Create a new instance
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->order_product = $this->order_product ? new OrderProduct((array)$this->order_product) : null;
    }

    /**
     * Delete a MembershipModification
     */
    public function delete(): object
    {
        try {
            GatewayService::delete('v2/membership-modifications/'.$this->id, [ 'throw_exception' => true ]);
        } catch (GuzzleException $e) {
            $message = json_decode($e->getResponse()->getBody()->getContents())->message ?? 'An error occurred trying to delete your modification.';

            return (object)[
                'success' => false,
                'message' => $message,
            ];
        }

        return (object)[ 'success' => true ];
    }
}
