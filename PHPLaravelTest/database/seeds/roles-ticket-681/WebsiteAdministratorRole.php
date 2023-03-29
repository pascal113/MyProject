<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class WebsiteAdministratorRole extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $websiteAdminRole = Role::where('name', 'employee')->firstOrFail();
        $websiteAdminRole->update([
            'name' => 'editor',
            'display_name' => 'Editor',
            'updated_at' => \Carbon\Carbon::now(),
        ]);
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
                        ->where('key', 'LIKE', '%_splashes')
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
                        ->where('key', 'LIKE', '%_product_variants')
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
                        ->where('key', 'LIKE', '%_file_categories')
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
                        ->where('key', 'LIKE', '%_files')
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
        $websiteAdminRole->permissions()->sync(
            $permissions->pluck('id')->all()
        );

        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $adminRole->update([ 'display_name' => 'Admin' ]);
        $permissionsToAdd = Permission::where(function ($query) {
            $query
                ->where('key', 'LIKE', '%_product_variants')
                ->where(function ($query) {
                    $query
                        ->where('key', 'LIKE', 'browse_%')
                        ->orWhere('key', 'LIKE', 'read_%')
                        ->orWhere('key', 'LIKE', 'edit_%')
                        ->orWhere('key', 'LIKE', 'delete_%');
                });
        })->get();
        $adminRole->permissions()->attach(
            $permissionsToAdd->pluck('id')->all()
        );
        $permissionsToRemove = Permission::where('key', 'LIKE', '%_users')->get();
        $adminRole->permissions()->detach(
            $permissionsToRemove->pluck('id')->all()
        );

        User::where('email', 'brownbear@theflowerpress.net')
            ->update([ 'email' => 'admin@theflowerpress.net' ]);

        User::where('email', 'stefan.kovalenko@brownbear.com')
            ->update([ 'role_id' => $websiteAdminRole->id ]);

        User::create([
            'role_id' => $websiteAdminRole->id,
            'email' => 'website-editor@theflowerpress.net',
        ]);
    }
}
