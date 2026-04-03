<?php

namespace App\Modules\WorkerCategory\Repositories;

use App\Models\WorkerCategory;

class WorkerCategoryRepository
{
    /**
     * Mengambil seluruh data kategori pekerjaan beserta relasinya.
     *
     * Catatan:
     * - eager load workerItems dan relasi terkait
     * - Menghindari N+1 query pada tampilan
     */
    public function getAll()
    {
        return WorkerCategory::query()
            ->with([
                'workerItems.workerCategory',
                'workerItems.ahsp',
            ])
            ->get();
    }

    /**
     * Mengambil satu kategori pekerjaan berdasarkan ID.
     *
     * Catatan:
     * - Menggunakan findOrFail untuk menjamin data tersedia
     */
    public function find($id)
    {
        return WorkerCategory::findOrFail($id);
    }

    /**
     * Mengecek duplikasi nama kategori (exclude ID tertentu).
     *
     * Digunakan untuk proses update.
     */
    public function existsByNameExcept($id, string $name)
    {
        return WorkerCategory::where('id', '!=', $id)
            ->where('name', $name)
            ->exists();
    }

    /**
     * Mengecek apakah nama kategori sudah ada.
     *
     * Digunakan untuk proses create.
     */
    public function existsByName(string $name)
    {
        return WorkerCategory::where('name', $name)->exists();
    }

    /**
     * Menyimpan data kategori pekerjaan baru.
     *
     * Catatan:
     * - Validasi dilakukan di layer service
     */
    public function create(array $data)
    {
        return WorkerCategory::create($data);
    }

    /**
     * Memperbarui data kategori pekerjaan.
     */
    public function update(WorkerCategory $workercategory, array $data)
    {
        return $workercategory->update($data);
    }

    /**
     * Menghapus data kategori pekerjaan.
     */
    public function delete(WorkerCategory $workercategory)
    {
        return $workercategory->delete();
    }
}
