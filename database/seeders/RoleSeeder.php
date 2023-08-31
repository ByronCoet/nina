<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $r = Role::create(['role_name' => 'superadmin',]);
        $r = Role::create(['role_name' => 'companyadmin',]);
        $r = Role::create(['role_name' => 'receptionist',]);
        $r = Role::create(['role_name' => 'donor',]);
    }
}
