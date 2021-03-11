<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        //create permission
        Permission::create(['name'  => 'see all user']);
        Permission::create(['name'  => 'register user']);
        Permission::create(['name'  => 'edit user']);
        Permission::create(['name'  => 'delete user']);
        Permission::create(['name'  => 'create post']);
        Permission::create(['name'  => 'see all post']);
        Permission::create(['name'  => 'update post']);
        Permission::create(['name'  => 'publish post']);
        Permission::create(['name'  => 'unpublish post']);
        Permission::create(['name'  => 'delete post']);
        Permission::create(['name'  => 'create category']);
        Permission::create(['name'  => 'update category']);
        Permission::create(['name'  => 'delete category']);

        $role1  = Role::create(['name'  => 'admin']);
        $role1->givePermissionTo(Permission::all());

        $role2 = Role::create(['name'  => 'writer']);
        $role2->givePermissionTo('see all post','create post', 'update post', 'publish post', 'unpublish post', 'delete post');
    }
}