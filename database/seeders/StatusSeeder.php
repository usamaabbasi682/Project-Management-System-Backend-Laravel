<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'pending', 'order' => 1,'allow_delete'=>false],
            ['name' => 'completed', 'order' => 2,'allow_delete'=>false],
        ];

        foreach ($statuses as $status) {
            Status::create($status);
        }
    }
}
