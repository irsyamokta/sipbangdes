<?php

namespace App\Modules\Tool\Repositories;

use App\Models\MasterTool;

class ToolRepository
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
     * Base query untuk MasterTool.
     *
     * Catatan:
     * - Digunakan ulang untuk konsistensi query
     * - Mendukung filter dinamis
     */
    private function baseQuery(?string $search)
    {
        return MasterTool::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            });
    }

    /**
     * Mengambil satu data berdasarkan ID.
     */
    public function find($id)
    {
        return MasterTool::findOrFail($id);
    }

    /**
     * Mengecek apakah nama alat sudah ada.
     *
     * Digunakan saat create.
     */
    public function existsByNameAndUnit(string $name, string $unit)
    {
        return MasterTool::query()->where('name', $name)->where('unit', $unit)->exists();
    }

    /**
     * Mengecek duplikasi nama alat (exclude ID tertentu).
     *
     * Digunakan saat update.
     */
    public function existsByNameAndUnitExcept($id, string $name, string $unit)
    {
        return MasterTool::query()->where('name', $name)
            ->where('unit', $unit)
            ->where('id', '!=', $id)
            ->exists();
    }

    /**
     * Menyimpan data baru.
     */
    public function create(array $data)
    {
        return MasterTool::create($data);
    }

    /**
     * Memperbarui data.
     *
     * Catatan:
     * - Mengembalikan instance model setelah update
     */
    public function update(MasterTool $tool, array $data)
    {
        $tool->update($data);
        return $tool;
    }

    /**
     * Menghapus data.
     */
    public function delete(MasterTool $tool)
    {
        return $tool->delete();
    }
}
