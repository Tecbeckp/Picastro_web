<?php

namespace Database\Seeders;

use App\Models\Bortle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BortleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=1; $i < 10 ; $i++) { 
            Bortle::create(
                [
                  'bortle_number'     => $i,
                  'created_at'         => now(),
                  'updated_at'          => now(),  
                ]
            );
        }
    }
}
