<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(LotterySeeder::class);

         $this->call([
            RolesPermissionsSeeder::class,
        ]);
        // User::factory(10)->create();

        $user=User::factory()->create([
            'name' => 'admin',
            'email' => 'test@admin.com',
        ]);

         $user->assignRole('admin');
    }
}
