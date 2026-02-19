<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materials = [
            ['name' => 'Semen', 'unit' => 'zak', 'price' => 60000],
            ['name' => 'Pasir', 'unit' => 'm³', 'price' => 350000],
            ['name' => 'Batu Split', 'unit' => 'm³', 'price' => 400000],
            ['name' => 'Bata Merah', 'unit' => 'bh', 'price' => 1500],
            ['name' => 'Besi Beton', 'unit' => 'kg', 'price' => 15000],
            ['name' => 'Kayu', 'unit' => 'm³', 'price' => 2500000],
            ['name' => 'Paku', 'unit' => 'kg', 'price' => 120000],
            ['name' => 'Cat Tembok', 'unit' => 'liter', 'price' => 85000],
            ['name' => 'Keramik', 'unit' => 'm²', 'price' => 95000],
            ['name' => 'Pipa PVC', 'unit' => 'm', 'price' => 25000],
            ['name' => 'Kawat', 'unit' => 'kg', 'price' => 50000],
            ['name' => 'Triplek', 'unit' => 'lembar', 'price' => 120000],
            ['name' => 'Besi Hollow', 'unit' => 'm', 'price' => 50000],
            ['name' => 'Kaca', 'unit' => 'm²', 'price' => 180000],
            ['name' => 'Pasang Kunci', 'unit' => 'set', 'price' => 25000],
        ];

        foreach ($materials as $index => $material) {
            DB::table('master_materials')->insert([
                'id' => (string) Str::uuid(),
                'code' => 'MAT-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'name' => $material['name'],
                'unit' => $material['unit'],
                'price' => $material['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
