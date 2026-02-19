<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            /* Panjang */
            ['name' => 'm', 'category' => 'panjang'],
            ['name' => 'cm', 'category' => 'panjang'],
            ['name' => 'mm', 'category' => 'panjang'],
            ['name' => 'km', 'category' => 'panjang'],

            /* Luas */
            ['name' => 'm²', 'category' => 'luas'],
            ['name' => 'cm²', 'category' => 'luas'],
            ['name' => 'ha', 'category' => 'luas'],

            /* Volume */
            ['name' => 'm³', 'category' => 'volume'],
            ['name' => 'liter', 'category' => 'volume'],
            ['name' => 'ml', 'category' => 'volume'],

            /* Berat */
            ['name' => 'kg', 'category' => 'berat'],
            ['name' => 'gram', 'category' => 'berat'],
            ['name' => 'ton', 'category' => 'berat'],

            /* Waktu */
            ['name' => 'jam', 'category' => 'waktu'],
            ['name' => 'hari', 'category' => 'waktu'],
            ['name' => 'minggu', 'category' => 'waktu'],
            ['name' => 'bulan', 'category' => 'waktu'],

            /* Jumlah */
            ['name' => 'unit', 'category' => 'jumlah'],
            ['name' => 'bh', 'category' => 'jumlah'],
            ['name' => 'pcs', 'category' => 'jumlah'],
            ['name' => 'set', 'category' => 'jumlah'],
            ['name' => 'paket', 'category' => 'jumlah'],

            /* Tenaga Kerja */
            ['name' => 'OH', 'category' => 'tenaga kerja'],
            ['name' => 'OJ', 'category' => 'tenaga kerja'],

            /* Kemasan */
            ['name' => 'zak', 'category' => 'kemasan'],
            ['name' => 'sak', 'category' => 'kemasan'],
            ['name' => 'box', 'category' => 'kemasan'],
            ['name' => 'roll', 'category' => 'kemasan'],

            /* Lainnya */
            ['name' => 'rit', 'category' => 'lainnya'],
            ['name' => 'titik', 'category' => 'lainnya'],
            ['name' => 'batang', 'category' => 'lainnya'],
            ['name' => 'lembar', 'category' => 'lainnya'],
            ['name' => 'lot', 'category' => 'lainnya'],
        ];

        foreach ($units as $index => $unit) {
            DB::table('master_units')->insert([
                'id' => (string) Str::uuid(),
                'code' => 'SAT-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'name' => $unit['name'],
                'category' => $unit['category'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
