<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Role;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        // Super Admin
        $role = Role::where('name', 'super-admin')->firstOrFail();

        $permissions = Permission::where('key', 'NOT LIKE', 'read_pages%')->get();

        $role->permissions()->sync(
            $permissions->pluck('id')->all()
        );

        // Admin
        $role = Role::where('name', 'admin')->firstOrFail();

        $permissions = Permission::where(function ($query) {
            $query
                ->where('key', 'browse_admin')
                ->orWhere(function ($query) {
                    $query
                        ->where('key', 'LIKE', '%_pages')
                        ->where(function ($query) {
                            $query
                                ->where('key', 'LIKE', 'browse_%')
                                ->orWhere('key', 'LIKE', 'edit_%')
                                ->orWhere('key', 'LIKE', 'add_%');
                        });
                })
                ->orWhere(function ($query) {
                    $query
                        ->where('key', 'LIKE', '%_pages_with_category_main')
                        ->where(function ($query) {
                            $query
                                ->where('key', 'LIKE', 'browse_%')
                                ->orWhere('key', 'LIKE', 'edit_%');
                        });
                })
                ->orWhere(function ($query) {
                    $query
                        ->where('key', 'LIKE', '%_pages_with_category_promo')
                        ->where(function ($query) {
                            $query
                                ->where('key', 'LIKE', 'browse_%')
                                ->orWhere('key', 'LIKE', 'edit_%')
                                ->orWhere('key', 'LIKE', 'add_%')
                                ->orWhere('key', 'LIKE', 'delete_%')
                                ->orWhere('key', 'LIKE', 'publish_%')
                                ->orWhere('key', 'LIKE', 'change-url_%');
                        });
                })
                ->orWhere(function ($query) {
                    $query
                        ->where('key', 'LIKE', '%_product_categories')
                        ->where(function ($query) {
                            $query
                                ->where('key', 'LIKE', 'browse_%')
                                ->orWhere('key', 'LIKE', 'edit_%');
                        });
                })
                ->orWhere(function ($query) {
                    $query
                        ->where('key', 'LIKE', '%_orders')
                        ->where(function ($query) {
                            $query
                                ->where('key', 'LIKE', 'browse_%')
                                ->orWhere('key', 'LIKE', 'read_%')
                                ->orWhere('key', 'LIKE', 'edit_%')
                                ->orWhere('key', 'LIKE', 'delete_%');
                        });
                })
                ->orWhere(function ($query) {
                    $query
                        ->where('key', 'LIKE', '%_products')
                        ->where(function ($query) {
                            $query
                                ->where('key', 'LIKE', 'browse_%')
                                ->orWhere('key', 'LIKE', 'read_%')
                                ->orWhere('key', 'LIKE', 'edit_%')
                                ->orWhere('key', 'LIKE', 'add_%')
                                ->orWhere('key', 'LIKE', 'delete_%');
                        });
                })
                ->orWhere(function ($query) {
                    $query
                        ->where('key', 'LIKE', '%_coupons')
                        ->where(function ($query) {
                            $query
                                ->where('key', 'LIKE', 'browse_%')
                                ->orWhere('key', 'LIKE', 'edit_%')
                                ->orWhere('key', 'LIKE', 'add_%')
                                ->orWhere('key', 'LIKE', 'delete_%');
                        });
                })
                ->orWhere(function ($query) {
                    $query
                        ->where('key', 'LIKE', '%_locations')
                        ->where(function ($query) {
                            $query
                                ->where('key', 'LIKE', 'browse_%')
                                ->orWhere('key', 'LIKE', 'edit_%')
                                ->orWhere('key', 'LIKE', 'add_%')
                                ->orWhere('key', 'LIKE', 'delete_%');
                        });
                })
                ->orWhere(function ($query) {
                    $query
                        ->where('key', 'LIKE', '%_media')
                        ->where(function ($query) {
                            $query
                                ->where('key', 'LIKE', 'browse_%')
                                ->orWhere('key', 'LIKE', 'edit_%')
                                ->orWhere('key', 'LIKE', 'add_%')
                                ->orWhere('key', 'LIKE', 'delete_%');
                        });
                })
                ->orWhere(function ($query) {
                    $query
                        ->where('key', 'LIKE', '%_users')
                        ->where(function ($query) {
                            $query
                                ->where('key', 'LIKE', 'browse_%')
                                ->orWhere('key', 'LIKE', 'read_%')
                                ->orWhere('key', 'LIKE', 'add_%');
                        });
                })
                ->orWhere(function ($query) {
                    $query
                        ->where('key', 'LIKE', '%_company_file_categories')
                        ->where(function ($query) {
                            $query
                                ->where('key', 'LIKE', 'browse_%')
                                ->orWhere('key', 'LIKE', 'edit_%')
                                ->orWhere('key', 'LIKE', 'add_%')
                                ->orWhere('key', 'LIKE', 'delete_%');
                        });
                })
                ->orWhere(function ($query) {
                    $query
                        ->where('key', 'LIKE', '%_company_files')
                        ->where(function ($query) {
                            $query
                                ->where('key', 'LIKE', 'browse_%')
                                ->orWhere('key', 'LIKE', 'edit_%')
                                ->orWhere('key', 'LIKE', 'add_%')
                                ->orWhere('key', 'LIKE', 'delete_%');
                        });
                })
                ->orWhere('key', 'frontend_company_files');
        })->get();

        $role->permissions()->sync(
            $permissions->pluck('id')->all()
        );

        // Employee
        $role = Role::where('name', 'employee')->firstOrFail();
        $permissions = Permission::where('key', 'frontend_company_files')->get();
        $role->permissions()->sync(
            $permissions->pluck('id')->all()
        );
    }
}
