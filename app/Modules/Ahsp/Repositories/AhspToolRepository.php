<?php

namespace App\Modules\Ahsp\Repositories;

use App\Models\Ahsp;
use App\Models\AhspComponentTool;

class AhspToolRepository
{
    /**
     * Mengambil seluruh alat berdasarkan AHSP ID.
     *
     * Catatan:
     * - Menggunakan relasi ahspComponentTools
     * - eager load masterTool untuk efisiensi query
     */
    public function getAhspTools($ahspId)
    {
        return Ahsp::findOrFail($ahspId)
            ->ahspComponentTools()
            ->with('masterTool')
            ->get();
    }

    /**
     * Mengambil satu data alat AHSP berdasarkan ID.
     *
     * Catatan:
     * - Menggunakan findOrFail untuk menjamin data tersedia
     */
    public function find($id)
    {
        return AhspComponentTool::findOrFail($id);
    }

    /**
     * Menyimpan data alat AHSP baru.
     *
     * Catatan:
     * - Validasi dilakukan di layer service
     */
    public function create(array $data)
    {
        return AhspComponentTool::create($data);
    }

    /**
     * Memperbarui data alat AHSP.
     *
     * Catatan:
     * - Return berupa boolean hasil update
     */
    public function update(AhspComponentTool $ahspTool, array $data)
    {
        return $ahspTool->update($data);
    }

    /**
     * Menghapus data alat AHSP.
     */
    public function delete(AhspComponentTool $ahspTool)
    {
        return $ahspTool->delete();
    }
}
