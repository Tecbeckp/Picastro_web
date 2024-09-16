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
                'icon'        => 'assets/uploads/telescope-icons/dobsonian.png',
                'created_at'  => now(),
                'updated_at'  => now()
            ],[
                'name'        => 'Reflector',
                'icon'        => 'assets/uploads/telescope-icons/reflector.png',
                'created_at'  => now(),
                'updated_at'  => now()
            ],[
                'name'        => 'Refractor',
                'icon'        => 'assets/uploads/telescope-icons/refractor.png',
                'created_at'  => now(),
                'updated_at'  => now()
            ],[
                'name'        => 'Newtonian',
                'icon'        => 'assets/uploads/telescope-icons/reflector.png',
                'created_at'  => now(),
                'updated_at'  => now()
            ],[
                'name'        => 'SCT',
                'icon'        => 'assets/uploads/telescope-icons/SCT.png',
                'created_at'  => now(),
                'updated_at'  => now()
            ],[
                'name'        => 'Maksutov',
                'icon'        => 'assets/uploads/telescope-icons/dobsonian.png',
                'created_at'  => now(),
                'updated_at'  => now()
            ],[
                'name'        => 'Smart Telescope',
                'icon'        => 'assets/uploads/telescope-icons/smart-telescope.png',
                'created_at'  => now(),
                'updated_at'  => now()
            ],[
                'name'        => 'Catadioptric',
                'icon'        => 'assets/uploads/telescope-icons/dobsonian.png',
                'created_at'  => now(),
                'updated_at'  => now()
            ]
        ]);
    }
}
