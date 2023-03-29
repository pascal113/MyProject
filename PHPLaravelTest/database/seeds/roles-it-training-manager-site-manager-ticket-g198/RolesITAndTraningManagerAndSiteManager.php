<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesITAndTraningManagerAndSiteManager extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'it',
            'display_name' => 'IT',
        ]);
        Role::create([
            'name' => 'site-manager',
            'display_name' => 'Site Manager',
        ]);
        Role::create([
            'name' => 'training-manager',
            'display_name' => 'Training Manager',
        ]);
    }
}
