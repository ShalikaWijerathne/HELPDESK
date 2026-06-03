<?php

namespace Database\Seeders;

use App\Models\Priority;
use Illuminate\Database\Seeder;

class PrioritySeeder extends Seeder
{
    public function run(): void
    {
        $priorities = [
            ['name' => 'Low',    'rank' => 1],
            ['name' => 'Medium', 'rank' => 2],
            ['name' => 'High',   'rank' => 3],
            ['name' => 'Urgent', 'rank' => 4],
        ];

        foreach ($priorities as $data) {
            Priority::firstOrCreate(['name' => $data['name']], ['rank' => $data['rank']]);
        }
    }
}
