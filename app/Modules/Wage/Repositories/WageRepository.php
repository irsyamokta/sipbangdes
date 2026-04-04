<?php

namespace App\Modules\Wage\Repositories;

use App\Models\MasterWage;

class WageRepository
{
    /**
     * Mengambil seluruh data tanpa pagination.
     */
    public function getAll(?string $search = null)
    {
        return $this->baseQuery($search)->get();
    }

    /**
     * Mengambil data dengan pagination.
     *
     * Catatan:
     * - Query string dipertahankan untuk kebutuhan filter frontend
     */
    public function getPaginated(?string $search = null, int $perPage = 10)
    {
        return $this->baseQuery($search)
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Mengambil data dengan pagination.
     *
     * Catatan:
     * - Query string dipertahankan untuk kebutuhan filter frontend
     */
    private function baseQuery(?string $search)
    {
        return MasterWage::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                        ->orWhere('position', 'like', "%{$search}%");
                });
            });
    }

    /**
     * Mengambil satu data berdasarkan ID.
     */
    public function find($id)
    {
        return MasterWage::findOrFail($id);
    }

    /**
     * Mengecek apakah nama jabatan sudah ada.
     *
     * Digunakan saat create.
     */
    public function existsByPosition(string $position)
    {
        return MasterWage::where('position', $position)->exists();
    }

    public function existsByPositionExcept($id, string $position)
    {
        return MasterWage::where('position', $position)
            ->where('id', '!=', $id)
            ->exists();
    }

    /**
     * Mengecek duplikasi nama jabatan (exclude ID tertentu).
     *
     * Digunakan saat update.
     */
    public function create(array $data)
    {
        return MasterWage::create($data);
    }

    /**
     * Mengecek duplikasi nama jabatan (exclude ID tertentu).
     *
     * Digunakan saat update.
     */
    public function update(MasterWage $wage, array $data)
    {
        $wage->update($data);
        return $wage;
    }

    /**
     * Memperbarui data.
     *
     * Catatan:
     * - Mengembalikan instance model setelah update
     */
    public function delete(MasterWage $wage)
    {
        return $wage->delete();
    }
}
