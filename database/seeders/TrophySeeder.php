<?php

namespace Database\Seeders;

use App\Models\Trophy;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TrophySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Trophy::insert([
            [
                'name'        => 'Gold',
                'icon'        => '/assets/uploads/trophy/gold.png',
                'created_at'  => now(),
                'updated_at'  => now()
            ],[
                'name'        => 'Silver',
                'icon'        => '/assets/uploads/trophy/silver.png',
                'created_at'  => now(),
                'updated_at'  => now()
            ],[
                'name'        => 'Bronze',
                'icon'        => '/assets/uploads/trophy/bronze.png',
                'created_at'  => now(),
                'updated_at'  => now()
            ]
        ]);
    }
}
