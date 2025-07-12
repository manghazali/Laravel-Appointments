<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    public function run()
    {
        $admin_permissions = Permission::all();
        Role::findOrFail(1)->permissions()->sync($admin_permissions->pluck('id'));

        $user_permissions = $admin_permissions->filter(function ($permission) {
            return in_array($permission->title, ['employee_access', 'appointment_access','appointment_show', 'employee_show']);
        });
        Role::findOrFail(2)->permissions()->sync($user_permissions->pluck('id'));

        $client_permissions = $admin_permissions->filter(function ($permission) {
            return substr($permission->title, 0, 12) === 'appointment_' && $permission->title !== 'appointment_delete';
        });
        Role::findOrFail(3)->permissions()->sync($client_permissions->pluck('id'));
    }
}
