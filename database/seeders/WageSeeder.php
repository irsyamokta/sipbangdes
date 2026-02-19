<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wages = [
            ['position' => 'Mandor', 'unit' => 'OH', 'price' => 250000],
            ['position' => 'Tukang Batu', 'unit' => 'OH', 'price' => 200000],
            ['position' => 'Tukang Kayu', 'unit' => 'OH', 'price' => 180000],
            ['position' => 'Tukang Besi', 'unit' => 'OH', 'price' => 200000],
            ['position' => 'Tukang Cat', 'unit' => 'OH', 'price' => 170000],
            ['position' => 'Tukang Pasang Keramik', 'unit' => 'OH', 'price' => 180000],
            ['position' => 'Pekerja Harian', 'unit' => 'OH', 'price' => 150000],
            ['position' => 'Operator Alat', 'unit' => 'OH', 'price' => 220000],
        ];

        foreach ($wages as $index => $wage) {
            DB::table('master_wages')->insert([
                'id' => (string) Str::uuid(),
                'code' => 'UPH-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'position' => $wage['position'],
                'unit' => $wage['unit'],
                'price' => $wage['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
