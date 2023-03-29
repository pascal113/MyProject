<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class CustomerRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::where('name', 'user')->update([
            'name' => 'customer',
            'display_name' => 'Customer',
            'updated_at' => \Carbon\Carbon::now(),
        ]);
    }
}
