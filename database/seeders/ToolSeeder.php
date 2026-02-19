<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ToolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tools = [
            ['name' => 'Excavator', 'unit' => 'hari', 'price' => 1500000],
            ['name' => 'Bulldozer', 'unit' => 'hari', 'price' => 2000000],
            ['name' => 'Roller', 'unit' => 'hari', 'price' => 1200000],
            ['name' => 'Crane', 'unit' => 'hari', 'price' => 2500000],
            ['name' => 'Mixer Beton', 'unit' => 'hari', 'price' => 800000],
            ['name' => 'Dump Truck', 'unit' => 'rit', 'price' => 1000000],
            ['name' => 'Compactor', 'unit' => 'hari', 'price' => 1100000],
            ['name' => 'Water Pump', 'unit' => 'hari', 'price' => 250000],
            ['name' => 'Generator', 'unit' => 'hari', 'price' => 300000],
            ['name' => 'Scaffolding', 'unit' => 'set', 'price' => 50000],
        ];

        foreach ($tools as $index => $tool) {
            DB::table('master_tools')->insert([
                'id' => (string) Str::uuid(),
                'code' => 'TL-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'name' => $tool['name'],
                'unit' => $tool['unit'],
                'price' => $tool['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
