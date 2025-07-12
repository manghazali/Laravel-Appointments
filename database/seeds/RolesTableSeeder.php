<?php

use App\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'id'         => 1,
                'title'      => 'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 2,
                'title'      => 'User',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 3,
                'title'      => 'Client',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrInsert(['id' => $role['id']], $role);
        }
    }
}
