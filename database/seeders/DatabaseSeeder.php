<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AdminUserSeeder::class);
        $this->call(ApproxLunarPhaseSeeder::class);
        $this->call(BortleSeeder::class);
        $this->call(ObjectTypeSeeder::class);
        $this->call(ObserverLocationSeeder::class);
        $this->call(TelescopeSeeder::class);
        $this->call(TrophySeeder::class);
      
    }
}
