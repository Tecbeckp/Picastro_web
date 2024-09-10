<?php

namespace Database\Seeders;

use App\Models\Telescope;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TelescopeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Telescope::insert([
            [
                'name'        => 'Dobsonian',
                'created_at'  => now(),
                'updated_at'  => now()
            ],[
                'name'        => 'Reflector',
                'created_at'  => now(),
                'updated_at'  => now()
            ],[
                'name'        => 'Refractor',
                'created_at'  => now(),
                'updated_at'  => now()
            ],[
                'name'        => 'Newtonian',
                'created_at'  => now(),
                'updated_at'  => now()
            ],[
                'name'        => 'SCT',
                'created_at'  => now(),
                'updated_at'  => now()
            ],[
                'name'        => 'Maksutov',
                'created_at'  => now(),
                'updated_at'  => now()
            ],[
                'name'        => 'Smart Telescope',
                'created_at'  => now(),
                'updated_at'  => now()
            ],[
                'name'        => 'Catadioptric',
                'created_at'  => now(),
                'updated_at'  => now()
            ]
        ]);
    }
}
