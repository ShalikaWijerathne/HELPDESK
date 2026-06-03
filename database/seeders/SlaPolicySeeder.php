<?php

namespace Database\Seeders;

use App\Models\Priority;
use App\Models\SlaPolicy;
use Illuminate\Database\Seeder;

class SlaPolicySeeder extends Seeder
{
    public function run(): void
    {
        $policies = [
            'Low'    => ['response' => 480,  'resolution' => 4320],
            'Medium' => ['response' => 240,  'resolution' => 1440],
            'High'   => ['response' => 60,   'resolution' => 480],
            'Urgent' => ['response' => 15,   'resolution' => 60],
        ];

        foreach ($policies as $priorityName => $times) {
            $priority = Priority::where('name', $priorityName)->first();

            if ($priority) {
                SlaPolicy::updateOrCreate(
                    ['priority_id' => $priority->id],
                    [
                        'response_minutes'   => $times['response'],
                        'resolution_minutes' => $times['resolution'],
                    ]
                );
            }
        }
    }
}
