<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class DisallowDeleteCoupon extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $deleteCouponsPermission = Permission::where('key', 'delete_coupons')->firstOrFail();

        Role::where('name', '!=', 'super-admin')
            ->get()
            ->each(function ($role) use ($deleteCouponsPermission) {
                $role->permissions()->detach($deleteCouponsPermission->id);
            });
    }
}
