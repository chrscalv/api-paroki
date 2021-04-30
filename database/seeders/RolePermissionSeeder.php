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
        Permission::create(['name'  => 'see category by role']);
        Permission::create(['name'  => 'delete category']);
        Permission::create(['name'  => 'see role']);
        Permission::create(['name'  => 'upload image']);
        Permission::create(['name'  => 'edit image']);
        Permission::create(['name'  => 'delete image']);
        Permission::create(['name'  => 'see berita paroki']);
        Permission::create(['name'  => 'see renungan']);
        Permission::create(['name'  => 'see information']);

        $role1  = Role::create(['name'  => 'admin']);
        $role1->givePermissionTo(Permission::all());

        $role2 = Role::create(['name'  => 'komsos']);
        $role2->givePermissionTo('see berita paroki', 'create post', 'update post', 'publish post', 'unpublish post', 'delete post');

        $role3 = Role::create(['name'  => 'pewartaan']);
        $role3->givePermissionTo('see renungan','create post', 'update post', 'publish post', 'unpublish post', 'delete post');

        $role4 = Role::create(['name'   => 'sekretariat']);
        $role4->givePermissionTo('see information', 'create post', 'update post', 'publish post', 'unpublish post', 'delete post');
    }
}