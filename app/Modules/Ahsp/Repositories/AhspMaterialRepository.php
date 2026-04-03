<?php

namespace App\Modules\Ahsp\Repositories;

use App\Models\Ahsp;
use App\Models\AhspComponentMaterial;

class AhspMaterialRepository
{
    /**
     * Mengambil seluruh material berdasarkan AHSP ID.
     *
     * Catatan:
     * - Menggunakan relasi ahspComponentMaterials
     * - eager load masterMaterial untuk efisiensi query
     */
    public function getAhspMaterials($ahspId)
    {
        return Ahsp::findOrFail($ahspId)
            ->ahspComponentMaterials()
            ->with('masterMaterial')
            ->get();
    }

    /**
     * Mengambil satu data material AHSP berdasarkan ID.
     *
     * Catatan:
     * - Menggunakan findOrFail untuk menjamin data tersedia
     */
    public function find($id)
    {
        return AhspComponentMaterial::findOrFail($id);
    }

    /**
     * Menyimpan data material AHSP baru.
     *
     * Catatan:
     * - Validasi dilakukan di layer service
     */
    public function create(array $data)
    {
        return AhspComponentMaterial::create($data);
    }


    /**
     * Memperbarui data material AHSP.
     *
     * Catatan:
     * - Return berupa boolean (hasil update)
     */
    public function update(AhspComponentMaterial $ahspMaterial, array $data)
    {
        return $ahspMaterial->update($data);
    }

    /**
     * Menghapus data material AHSP.
     */
    public function delete(AhspComponentMaterial $ahspMaterial)
    {
        return $ahspMaterial->delete();
    }
}
