<?php

namespace Database\Seeders;

use App\Models\ObserverLocation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ObserverLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ObserverLocation::insert([
            [
                'name'        => 'Europe',
                'created_at'  => now(),
                'updated_at'  => now()
            ],[
                'name'        => 'Asia',
                'created_at'  => now(),
                'updated_at'  => now()
            ],[
                'name'        => 'N. America',
                'created_at'  => now(),
                'updated_at'  => now()
            ],[
                'name'        => 'Canada',
                'created_at'  => now(),
                'updated_at'  => now()
            ],[
                'name'        => 'Australia',
                'created_at'  => now(),
                'updated_at'  => now()
            ]
        ]);
    }
}
