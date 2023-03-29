<?php

use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class StaffRole extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::where('name', 'editor')->update([
            'name' => 'staff',
            'display_name' => 'Staff',
            'updated_at' => Carbon::now(),
        ]);
    }
}
