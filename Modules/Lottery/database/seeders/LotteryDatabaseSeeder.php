<?php

namespace Modules\Lottery\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Lottery\Models\Lottery;

class LotteryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $this->call(LotterySeeder::class);
    }
}