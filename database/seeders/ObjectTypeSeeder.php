<?php

namespace Database\Seeders;

use App\Models\ObjectType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ObjectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ObjectType::insert([
            [
                'name'          => 'Nebula',
                'icon'          => 'assets/uploads/icons/nebula.png',
                'created_at'    => now(),
                'updated_at'    => now()
            ], [
                'name'          => 'Galaxy',
                'icon'          => 'assets/uploads/icons/galaxy.png',
                'created_at'    => now(),
                'updated_at'    => now()
            ], [
                'name'          => 'Widefield',
                'icon'          => 'assets/uploads/icons/galaxy.png',
                'created_at'    => now(),
                'updated_at'    => now()
            ], [
                'name'          => 'Milkyway',
                'icon'          => 'assets/uploads/icons/galaxy.png',
                'created_at'    => now(),
                'updated_at'    => now()
            ], [
                'name'          => 'Asterism',
                'icon'          => 'assets/uploads/icons/asterism.png',
                'created_at'    => now(),
                'updated_at'    => now()
            ], [
                'name'          => 'Double Stars',
                'icon'          => 'assets/uploads/icons/star.png',
                'created_at'    => now(),
                'updated_at'    => now()
            ], [
                'name'          => 'Lunar',
                'icon'          => 'assets/uploads/icons/lunar.png',
                'created_at'    => now(),
                'updated_at'    => now()
            ], [
                'name'          => 'Solar',
                'icon'          => 'assets/uploads/icons/solar.png',
                'created_at'    => now(),
                'updated_at'    => now()
            ], [
                'name'          => 'ISS',
                'icon'          => 'assets/uploads/icons/ISS-transits.png',
                'created_at'    => now(),
                'updated_at'    => now()
            ], [
                'name'          => 'Planetary',
                'icon'          => 'assets/uploads/icons/planetary.png',
                'created_at'    => now(),
                'updated_at'    => now()
            ], [
                'name'          => 'Comets',
                'icon'          => 'assets/uploads/icons/comets.png',
                'created_at'    => now(),
                'updated_at'    => now()
            ], [
                'name'          => 'Cluster',
                'icon'          => 'assets/uploads/icons/star.png',
                'created_at'    => now(),
                'updated_at'    => now()
            ]
        ]);
    }
}
