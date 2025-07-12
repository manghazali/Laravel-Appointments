<?php

use App\Status;
use Illuminate\Database\Seeder;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = [
            [
                'id'         => '1',
                'name'       => 'Approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '2',
                'name'       => 'Rejected',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '3',
                'name'       => 'Pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '4',
                'name'       => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '5',
                'name'       => 'Inactive',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '6',
                'name'       => 'Canceled',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '7',
                'name'       => 'Completed',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($status as $item) {
            Status::updateOrCreate(
                ['id' => $item['id']],
                $item
            );
        }
    }
}
