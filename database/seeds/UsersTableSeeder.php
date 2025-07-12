<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'             => 1,
                'name'           => 'Admin',
                'email'          => 'admin@gmail.com',
                'password'       => '$2y$10$Y.jEitizf.DW3V7gxCnMr.SdWN2i1w4gobo28vTLGaFajqcjUl8Oy',
                'remember_token' => null,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']], // Check for existing user by email
                $user // Update or insert the user
            );
        }
    }
}
