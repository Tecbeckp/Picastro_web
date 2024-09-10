<?php

namespace Database\Seeders;

use App\Models\ApproxLunarPhase;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApproxLunarPhaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i <= 100; $i += 5) { 
            ApproxLunarPhase::create(
                [
                    'number' => $i,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]
            );
        }
    }
}
