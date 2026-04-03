<?php

namespace App\Modules\Unit\Repositories;

use App\Models\MasterUnit;

class UnitRepository
{
    /**
     * Mengambil seluruh data satuan tanpa pagination.
     */
    public function getAll(?string $search = null)
    {
        return $this->baseQuery($search)->get();
    }

    /**
     * Mengambil data satuan dengan pagination.
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
     * Base query untuk MasterUnit.
     *
     * Catatan:
     * - Digunakan ulang untuk getAll & getPaginated
     * - Mendukung filter dinamis berdasarkan search
     */
    private function baseQuery(?string $search)
    {
        return MasterUnit::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            });
    }

    /**
     * Mengambil satu data berdasarkan ID.
     */
    public function find($id)
    {
        return MasterUnit::findOrFail($id);
    }

    /**
     * Mengecek apakah nama satuan sudah ada.
     *
     * Digunakan saat create.
     */
    public function existsByName(string $name)
    {
        return MasterUnit::where('name', $name)->exists();
    }

    /**
     * Mengecek duplikasi nama satuan (exclude ID tertentu).
     *
     * Digunakan saat update.
     */
    public function existsByNameExcept($id, string $name)
    {
        return MasterUnit::where('name', $name)
            ->where('id', '!=', $id)
            ->exists();
    }

    /**
     * Menyimpan data satuan baru.
     */
    public function create(array $data)
    {
        return MasterUnit::create($data);
    }

    /**
     * Memperbarui data satuan.
     *
     * Catatan:
     * - Mengembalikan instance model setelah update
     */
    public function update(MasterUnit $unit, array $data)
    {
        $unit->update($data);
        return $unit;
    }

    /**
     * Menghapus data satuan.
     */
    public function delete(MasterUnit $unit)
    {
        return $unit->delete();
    }
}
