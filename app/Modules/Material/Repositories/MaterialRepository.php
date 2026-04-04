<?php

namespace App\Modules\Material\Repositories;

use App\Models\MasterMaterial;

class MaterialRepository
{
    /**
     * Mengambil seluruh data material tanpa pagination.
     */
    public function getAll(?string $search = null)
    {
        return $this->baseQuery($search)->get();
    }

    /**
     * Mengambil data material dengan pagination.
     *
     * Catatan:
     * - Query string dipertahankan untuk kebutuhan filter di frontend
     */
    public function getPaginated(?string $search = null, int $perPage = 10)
    {
        return $this->baseQuery($search)
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Base query untuk MasterMaterial.
     *
     * Catatan:
     * - Digunakan ulang untuk konsistensi query
     * - Mendukung filter dinamis
     */
    private function baseQuery(?string $search)
    {
        return MasterMaterial::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            });
    }

    /**
     * Mengambil satu data material berdasarkan ID.
     */
    public function find($id)
    {
        return MasterMaterial::findOrFail($id);
    }

    /**
     * Mengecek apakah nama material sudah ada.
     *
     * Digunakan saat create.
     */
    public function existsByName(string $name)
    {
        return MasterMaterial::where('name', $name)->exists();
    }

    /**
     * Mengecek duplikasi nama material (exclude ID tertentu).
     *
     * Digunakan saat update.
     */
    public function existsByNameExcept($id, string $name)
    {
        return MasterMaterial::where('name', $name)
            ->where('id', '!=', $id)
            ->exists();
    }

    /**
     * Menyimpan data material baru.
     */
    public function create(array $data)
    {
        return MasterMaterial::create($data);
    }

    /**
     * Memperbarui data material.
     *
     * Catatan:
     * - Mengembalikan instance model setelah update
     */
    public function update(MasterMaterial $material, array $data)
    {
        $material->update($data);
        return $material;
    }

    /**
     * Menghapus data material.
     */
    public function delete(MasterMaterial $material)
    {
        return $material->delete();
    }
}
