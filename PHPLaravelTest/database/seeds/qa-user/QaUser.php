<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class QaUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Super Admin
        $role = Role::where('name', 'super-admin')->firstOrFail();
        User::create([
            'email' => 'qa@theflowerpress.net',
            'role_id' => $role->id,
        ]);
    }
}
