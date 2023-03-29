<?php

namespace App\Policies;

use App\Models\User;
use TCG\Voyager\Policies\BasePolicy as BaseBasePolicy;

class BasePolicy extends BaseBasePolicy
{
    /**
     * Can $user delete $model?
     */
    public static function bulk_delete(User $user, $model): bool
    {
        return $user->hasPermission('bulk_delete');
    }
}
