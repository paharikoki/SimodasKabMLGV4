<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'user']);

        Permission::firstOrCreate(['name' => 'view-users']);
        Permission::firstOrCreate(['name' => 'create-users']);
        Permission::firstOrCreate(['name' => 'edit-users']);
        Permission::firstOrCreate(['name' => 'delete-users']);
        Permission::firstOrCreate(['name' => 'create-assets']);
        Permission::firstOrCreate(['name' => 'import-assets']);
        Permission::firstOrCreate(['name' => 'edit-assets']);
        Permission::firstOrCreate(['name' => 'delete-assets']);
        Permission::firstOrCreate(['name' => 'create-bast']);
        Permission::firstOrCreate(['name' => 'edit-bast']);
        Permission::firstOrCreate(['name' => 'delete-bast']);
        Permission::firstOrCreate(['name' => 'edit-transaction']);
        Permission::firstOrCreate(['name' => 'delete-transaction']);
        Permission::firstOrCreate(['name' => 'inventaris']);
        Permission::firstOrCreate(['name' => 'ruang']);

        $user = User::find(1);
        $user->assignRole('admin');

        // $adminRole = Role::firstOrCreate(['name' => 'ap

        // $userRole = Role::firstOrCreate(['name' => 'user']);
        // $userRole->givePermissionTo('view-users');
        // $userRole->givePermissionTo('edit-assets');
        // $userRole->givePermissionTo('delete-assets');
        // $userRole->givePermissionTo('create-bast');

    }
}
