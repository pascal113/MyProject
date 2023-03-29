<?php

namespace App\Models;

use App\GatewayModel;
use App\Models\MembershipModification;
use App\Models\ProductVariant;
use App\Services\GatewayService;
use FPCS\FlexiblePageCms\Services\CmsRoute;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class Membership extends GatewayModel
{
    /**
     * !!
     * !! This is a "phantom" model and is not tied directly to anything in the database.
     * !!
     *
     * @var string
     */
    protected $table = null;

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

        $this->pending_modification = $this->pending_modification ? new MembershipModification((array)$this->pending_modification) : null;
    }

    /**
     * Return all Memberships for the current user
     */
    public static function getForCurrentUser(): Collection
    {
        $gatewayMemberships = GatewayService::get('/v2/user/memberships')->data ?? [];

        $memberships = collect($gatewayMemberships ?? [])->map(function ($gatewayMembership) {
            return new Membership((array)$gatewayMembership);
        });

        return $memberships;
    }

    /**
     * Return a specific Membership that belongs to the current user by id or wash_connect_id
     */
    public static function getForCurrentUserById(string $idOrWashConnectId): ?Membership
    {
        $membership = GatewayService::get('/v2/user/memberships/'.$idOrWashConnectId)->data ?? null;

        if (!$membership) {
            return null;
        }

        return new Membership((array)$membership);
    }

    /**
     * Return a specific Membership by customer email and membership id
     */
    public static function getByWashConnectIdAndCustomerEmail(string $washConnectId, string $email): ?Membership
    {
        $membership = GatewayService::get('/v2/memberships/'.$email.'/'.$washConnectId, [ 'useAppToken' => true ])->data ?? null;

        if (!$membership) {
            return null;
        }

        return new Membership((array)$membership);
    }

    /**
     * Terminate a Membership
     */
    public function terminate(array $info): object
    {
        try {
            GatewayService::post('/v2/user/memberships/'.$this->wash_connect->membership->id.'/terminate', $info, [ 'throw_exception' => true ]);
        } catch (GuzzleException $e) {
            $message = json_decode($e->getResponse()->getBody()->getContents())->message ?? 'An error occurred trying to terminate your membership.';

            return (object)[
                'success' => false,
                'message' => $message,
            ];
        }

        return (object)[ 'success' => true ];
    }

    /**
     * Cancel a pending Membership Termination
     */
    public function cancelTermination(): object
    {
        try {
            GatewayService::delete('/v2/user/memberships/'.$this->wash_connect->membership->id.'/terminate', [ 'throw_exception' => true ]);
        } catch (GuzzleException $e) {
            $message = json_decode($e->getResponse()->getBody()->getContents())->message ?? 'An error occurred trying to delete your modification.';

            return (object)[
                'success' => false,
                'message' => $message,
            ];
        }

        return (object)[ 'success' => true ];
    }

    /**
     * Carbonize ->expires_at
     */
    public function getExpiresAtAttribute(): ?Carbon
    {
        return ($this->attributes['expires_at'] ?? null) ? Carbon::parse($this->attributes['expires_at']) : null;
    }

    /**
     * Carbonize ->last_membership_day
     */
    public function getLastMembershipDayAttribute(): ?Carbon
    {
        return ($this->attributes['last_membership_day'] ?? null) ? Carbon::parse($this->attributes['last_membership_day']) : null;
    }

    /**
     * Return Collection of Products that this Membership can be changed to
     */
    public function variantsAvailableForModifyingTo(): Collection
    {
        $rfid = $this->wash_connect->vehicle->rfid ?? null;

        if (!$rfid) {
            return collect([]);
        }

        $url = 'v2/memberships/'.urlencode($rfid).'/can-be-modified-to';

        return collect(GatewayService::get($url, [ 'useAppToken' => true ])->data ?? []);
    }

    /**
     * Can this Membership be modified to the passed ProductVariant?
     */
    public function canBeModifiedTo(?ProductVariant $toVariant = null): bool
    {
        if (!$toVariant) {
            return false;
        }

        $rfid = $this->wash_connect->vehicle->rfid ?? null;

        if (!$rfid) {
            return false;
        }

        $url = 'v2/memberships/'.urlencode($rfid).'/can-be-modified-to/'.urlencode($toVariant->slug);

        return GatewayService::get($url, [ 'useAppToken' => true ])->data ?? false;
    }

    /**
     * Generate human-friendly membership status details
     */
    public function getStatusDetailsAttribute(): string
    {
        if (!($this->wash_connect->membership ?? null)) {
            return 'Pending Activation';
        }

        $expiresAt = $this->expires_at;
        $expirationText = $expiresAt ? $expiresAt->format(config('format.date')) : '';
        $lastMembershipDay = $this->last_membership_day ? $this->last_membership_day->format(config('format.date')) : '';

        if (($this->wash_connect->membership->status ?? null) === 'Terminated') {
            if ($expiresAt > Carbon::now()) {
                return 'Pending termination, the last day to use your membership is '.$lastMembershipDay;
            } else {
                return 'Terminated';
            }
        }

        if ($this->is_active) {
            if ($expiresAt > Carbon::now()) {
                if ($this->club->is_recurring ?? null) {
                    return 'Active, renews '.$expirationText;
                }

                return 'Active, expires '.$expirationText;
            }

            if ($this->club->is_recurring ?? null) {
                return 'Failed renewal on '.$expirationText;
            }

            return 'Expired';
        }

        return $this->wash_connect->membership->status ?? 'Unknown';
    }

    /**
     * Generate human-friendly billing details
     */
    public function getBillingDetailsAttribute(): string
    {
        if (!($this->wash_connect->membership ?? null)) {
            return 'Billing details are pending';
        }

        $expiresAt = $this->expires_at;
        $expirationText = $expiresAt ? $expiresAt->format(config('format.date')) : '';
        $lastMembershipDay = $this->last_membership_day ? $this->last_membership_day->format(config('format.date')) : '';

        if (($this->wash_connect->membership->status ?? null) === 'Terminated') {
            if ($expiresAt > Carbon::now()) {
                return 'This membership is pending termination<br>'.
                    'The last day to use your membership is '.$lastMembershipDay;
            } else {
                return 'This membership has been terminated';
            }
        }

        if ($this->is_active) {
            $cardName = ($this->wash_connect->payment_method->brand ?? null ? 'The '.$this->wash_connect->payment_method->brand : 'Your').' card'.($this->wash_connect->payment_method->last4 ?? null ? ' ending in '.$this->wash_connect->payment_method->last4 : '');

            if ($expiresAt > Carbon::now()) {
                if ($this->club->is_recurring ?? null) {
                    return $cardName.' will be billed on '.$expirationText.'<br>'.
                        'This membership is active until terminated';
                }

                return 'Your card was billed<br>'.
                    'One year membership ends on '.$expirationText;
            }

            if ($this->club->is_recurring ?? null) {
                return $cardName.' failed to bill<br>'.
                    'Please take a look at your <a href="'.route('my-account.payment-methods.show').'">payment method</a> or <a href="'.CmsRoute::get('support/contact-us').'?show=email">contact support</a>';
            }

            return 'This membership has been terminated';
        }

        return $this->wash_connect->membership->status ?? 'Unknown';
    }
}
