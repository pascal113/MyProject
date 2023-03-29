<?php

declare(strict_types=1);

namespace App\Models;

use App\Events\UserCreated;
use App\Models\GatewayUser;
use App\Models\Location;
use App\Models\Permission;
use App\Services\GatewayService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Models\User as VoyagerUser;

class User extends VoyagerUser
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id',
        'email',
        'notification_pref_orders',
        'notification_pref_promotions',
        'notification_pref_marketing',
    ];

    /**
     * Disable Remember Me feature
     *
     * @var boolean
     */
    protected $rememberTokenName = false;

    /**
     * Eloquent event listeners
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => UserCreated::class,
    ];

    /**
     * Ordered list of roles, with higher-access roles first and lower-access roles last
     *
     * @var array
     */
    public static $rolesHierarchy = [
        'super-admin',
        'admin',
        'it',
        'staff',
        'site-manager',
        'training-manager',
        'customer',
    ];

    /**
     * ->gateway_user
     */
    public function gateway_user(): BelongsTo
    {
        return $this->belongsTo(GatewayUser::class, 'email', 'email');
    }

    /**
     * Permissions
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_user', 'user_id', 'permission_id');
    }

    /**
     * Home Location
     */
    public function home_location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'home_location_id');
    }

    /**
     * Does the user have permission to perform an action?
     */
    public function hasPermission($name): bool
    {
        return $this->all_permissions->contains($name);
    }

    /**
     * ->first_name
     */
    public function getFirstNameAttribute()
    {
        return $this->gateway_user->first_name ?? null;
    }

    /**
     * ->last_name
     */
    public function getLastNameAttribute()
    {
        return $this->gateway_user->last_name ?? null;
    }

    /**
     * ->full_name
     */
    public function getFullNameAttribute()
    {
        return $this->first_name.($this->first_name && $this->last_name ? ' ' : '').$this->last_name;
    }

    /**
     * ->shipping_first_name
     */
    public function getShippingFirstNameAttribute()
    {
        return $this->gateway_user->shipping_first_name ?? null;
    }

    /**
     * ->shipping_last_name
     */
    public function getShippingLastNameAttribute()
    {
        return $this->gateway_user->shipping_last_name ?? null;
    }

    /**
     * ->shipping_full_name
     */
    public function getShippingFullNameAttribute()
    {
        return $this->shipping_first_name.($this->shipping_first_name && $this->shipping_last_name ? ' ' : '').$this->shipping_last_name;
    }

    /**
     * ->avatar
     */
    public function getAvatarAttribute($value): ?string
    {
        return $this->gateway_user->avatar ?? null;
    }

    /**
     * ->address
     */
    public function getAddressAttribute()
    {
        return $this->gateway_user->address ?? null;
    }

    /**
     * ->city
     */
    public function getCityAttribute()
    {
        return $this->gateway_user->city ?? null;
    }

    /**
     * ->state
     */
    public function getStateAttribute()
    {
        return $this->gateway_user->state ?? null;
    }

    /**
     * ->zip
     */
    public function getZipAttribute()
    {
        return $this->gateway_user->zip ?? null;
    }

    /**
     * ->address_line_2
     */
    public function getAddressLine2Attribute(): ?string
    {
        return ($this->city ?? '').($this->city && $this->state ? ', ' : '').($this->state ?? '').($this->state && $this->zip ? ' ' : '').($this->zip ?? '');
    }

    /**
     * ->phone
     */
    public function getPhoneAttribute()
    {
        return $this->gateway_user->phone ?? null;
    }

    /**
     * ->shipping
     */
    public function getShippingAttribute()
    {
        return (object)[
            'first_name' => $this->shipping_first_name,
            'last_name' => $this->shipping_last_name,
            'full_name' => $this->shipping_full_name,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'zip' => $this->zip,
            'phone' => $this->phone,
        ];
    }

    /**
     * ->billing_zip
     */
    public function getBillingZipAttribute(): ?string
    {
        return $this->gateway_user->billing_zip ?? null;
    }

    /**
     * ->all_permissions

     * Returns all permissions for the given user (both from their Role and permission_user pivot table)
     */
    public function getAllPermissionsAttribute(): Collection
    {
        $rolePermissions = $this->roles_all()
            ->pluck('permissions')
            ->flatten()
            ->pluck('key');

        $permissions = collect($this->permissions->pluck('key'))
            ->merge($rolePermissions)
            ->unique();

        return $permissions;
    }

    /**
     * ->can_view_admin_landing attribute
     *
     * Can user view the Admin Landing page?
     */
    public function getCanViewAdminLandingAttribute(): bool
    {
        return $this->canViewGatewayAdmin;
    }

    /**
     * ->can_view_gateway_admin attribute
     *
     * Can user view Gateway admin?
     */
    public function getCanViewGatewayAdminAttribute(): bool
    {
        $gatewayPermissions = $this->gatewayPermissions;

        $canViewGatewayAdmin = in_array('browse_admin', $gatewayPermissions);

        return $canViewGatewayAdmin;
    }

    /**
     * ->gateway_permissions attribute
     */
    public function getGatewayPermissionsAttribute(): array
    {
        $gatewayPermissions = [];
        try {
            $gatewayPermissions = GatewayService::get('/v2/user/permissions')->data;
        } catch (\Exception $e) {
        }

        return $gatewayPermissions;
    }

    /**
     * ->wash_connect
     *
     * Return WashConnect data
     */
    public function getWashConnectAttribute(): ?object
    {
        if (!($this->email ?? null)) {
            return null;
        }

        $user = GatewayService::get('/v2/users/'.$this->email);

        if (!($user->data ?? null)) {
            return null;
        }

        return $user->data->wash_connect ?? null;
    }

    /**
     * ->masked_email
     *
     * Returns masked email address example as***@example.com
     */
    public function getMaskedEmailAttribute(): string
    {
        return substr($this->email, 0, 2).'***'.substr($this->email, strpos($this->email, '@'));
    }

    /**
     * ->email_vertified_at
     *
     * Has this user completed email verification?
     *
     * @return string|null
     */
    public function getEmailVerifiedAtAttribute()
    {
        return Auth::check() && Auth::user()->email === $this->email ? (GatewayService::user(true)->email_verified_at ?? null) : null;
    }

    /**
     * Is the passed user the same as the currently-logged-in user?
     */
    public static function isMe(User $user): bool
    {
        return Auth::check() && (Auth::user()->email ?? null) === ($user->email ?? null);
    }

    /**
     * Return value from OAuth user
     */
    public static function currentOAuthUserAttribute(string $attributeName)
    {
        return GatewayService::user()->{$attributeName} ?? null;
    }

    /**
     * Fetch user data by search string from Gateway
     */
    public static function searchGateway(?string $search = ''): ?array
    {
        $response = GatewayService::get('/v2/users/search/'.$search, [ 'throw_exception' => true ]);

        GatewayService::saveUsersToCache($response);

        return $response->data ?? [];
    }

    /**
     * Fetch user data from Gateway
     */
    public function getFromGateway(): ?object
    {
        if (!($this->email ?? null)) {
            return null;
        }

        $user = GatewayService::get('/v2/users/'.$this->email);

        if (!($user->data ?? null)) {
            return null;
        }

        return $user->data;
    }
}
