<?php

namespace App\Modules\WorkerCategory\Repositories;

use App\Models\WorkerCategory;
use App\Models\WorkerItem;

class WorkerItemRepository
{
    /**
     * Mengambil seluruh item pekerjaan berdasarkan kategori.
     *
     * Catatan:
     * - Menggunakan relasi workerItems dari WorkerCategory
     * - eager load workerCategory untuk kebutuhan relasi di frontend
     */
    public function getWorkerItems($workerCategoryId)
    {
        return WorkerCategory::findOrFail($workerCategoryId)
            ->workerItems()
            ->get();
    }

    /**
     * Mengambil satu item pekerjaan berdasarkan ID.
     *
     * Catatan:
     * - Menggunakan findOrFail untuk menjamin data tersedia
     */
    public function find($id)
    {
        return WorkerItem::findOrFail($id);
    }

    /**
     * Mengecek duplikasi nama pekerjaan dalam kategori (exclude ID tertentu).
     *
     * Digunakan untuk proses update.
     */
    public function existsByNameExcept($id, string $workName, string $categoryId)
    {
        return WorkerItem::where('id', '!=', $id)
            ->where('work_name', $workName)
            ->where('category_id', $categoryId)
            ->exists();
    }

    /**
     * Mengecek apakah nama pekerjaan sudah ada di dalam kategori.
     *
     * Digunakan untuk proses create.
     */
    public function existsByName(string $workName, string $categoryId)
    {
        return WorkerItem::where('work_name', $workName)
            ->where('category_id', $categoryId)
            ->exists();
    }

    /**
     * Menyimpan item pekerjaan baru.
     *
     * Catatan:
     * - Validasi dilakukan di layer service
     */
    public function create(array $data)
    {
        return WorkerItem::create($data);
    }

    /**
     * Memperbarui item pekerjaan.
     */
    public function update(WorkerItem $workerItem, array $data)
    {
        return $workerItem->update($data);
    }

    /**
     * Menghapus item pekerjaan.
     */
    public function delete(WorkerItem $workerItem)
    {
        return $workerItem->delete();
    }
}
