<?php

declare(strict_types=1);

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Create Super Admin
        $role = Role::where('name', 'super-admin')->firstOrFail();
        User::create([
            'email' => 'dev@theflowerpress.net',
            'role_id' => $role->id,
        ]);

        // Create Admin
        $role = Role::where('name', 'admin')->firstOrFail();
        User::create([
            'email' => 'brownbear@theflowerpress.net',
            'role_id' => $role->id,
        ]);
    }
}
