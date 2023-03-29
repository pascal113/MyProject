<?php

namespace App\Policies;

use App\Policies\BasePolicy;
use TCG\Voyager\Contracts\User;

class UserPolicy extends \TCG\Voyager\Policies\UserPolicy
{
    /**
     * Prevent non-admin from editing admin
     */
    public function edit(User $user, $model): bool // phpcs:ignore Squiz.Commenting.FunctionComment.TypeHintMissing
    {
        // Is a non-admin attempting to modify an admin account?
        if ($model->role && $user->role && stristr($model->role->name, 'admin') && !stristr($user->role->name, 'admin')) {
            return false;
        }

        // Is a non-super-admin attempting to modify a super-admin account?
        if ($model->role && $user->role && $model->role->name === 'super-admin' && $user->role->name !== 'super-admin') {
            return false;
        }

        return true;
    }

    /**
     * Prevent non-admin from deleting admin
     */
    public function delete(User $user, $model): bool // phpcs:ignore Squiz.Commenting.FunctionComment.TypeHintMissing
    {
        // Is a non-admin attempting to modify an admin account?
        if ($model->role && $user->role && stristr($model->role->name, 'admin') && !stristr($user->role->name, 'admin')) {
            return false;
        }

        // Is a non-super-admin attempting to modify a super-admin account?
        if ($model->role && $user->role && $model->role->name === 'super-admin' && $user->role->name !== 'super-admin') {
            return false;
        }

        return parent::delete($user, $model);
    }

    /**
     * Can $user bulk delete $model?
     */
    public function bulk_delete(User $user, $model): bool
    {
        return BasePolicy::bulk_delete($user, $model);
    }
}
