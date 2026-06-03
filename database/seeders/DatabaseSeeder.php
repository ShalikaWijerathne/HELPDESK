<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            CategorySeeder::class,
            PrioritySeeder::class,
            SlaPolicySeeder::class,
            UserSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('Demo accounts:');
        $this->command->info('  user@helpdesk.test  / password');
        $this->command->info('  staff@helpdesk.test / password');
        $this->command->info('  admin@helpdesk.test / password');
    }
}
