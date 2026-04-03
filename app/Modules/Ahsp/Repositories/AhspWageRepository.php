<?php

namespace App\Modules\Ahsp\Repositories;

use App\Models\Ahsp;
use App\Models\AhspComponentWage;

class AhspWageRepository
{
    /**
     * Mengambil seluruh data upah berdasarkan AHSP ID.
     *
     * Catatan:
     * - Menggunakan relasi ahspComponentWages
     * - eager load masterWage untuk efisiensi query
     */
    public function getAhspWages($ahspId)
    {
        return Ahsp::findOrFail($ahspId)
            ->ahspComponentWages()
            ->with('masterWage')
            ->get();
    }

    /**
     * Mengambil satu data upah AHSP berdasarkan ID.
     *
     * Catatan:
     * - Menggunakan findOrFail untuk menjamin data tersedia
     */
    public function find($id)
    {
        return AhspComponentWage::findOrFail($id);
    }

    /**
     * Menyimpan data upah AHSP baru.
     *
     * Catatan:
     * - Validasi dilakukan di layer service
     */
    public function create(array $data)
    {
        return AhspComponentWage::create($data);
    }

    /**
     * Memperbarui data upah AHSP.
     *
     * Catatan:
     * - Return berupa boolean hasil update
     */
    public function update(AhspComponentWage $ahspWage, array $data)
    {
        return $ahspWage->update($data);
    }

    /**
     * Menghapus data upah AHSP.
     */
    public function delete(AhspComponentWage $ahspWage)
    {
        return $ahspWage->delete();
    }
}
