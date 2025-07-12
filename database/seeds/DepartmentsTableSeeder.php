<?php

use App\Department;
use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = [
            [
                'id'         => '1',
                'name'       => 'Financing',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '2',
                'name'       => 'Facilitation',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($departments as $item) {
            Department::updateOrCreate(
                ['id' => $item['id']],
                $item
            );
        }
    }
}
