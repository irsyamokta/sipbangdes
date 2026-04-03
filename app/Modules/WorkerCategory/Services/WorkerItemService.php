<?php

namespace App\Modules\WorkerCategory\Services;

use DomainException;
use App\Modules\WorkerCategory\Repositories\WorkerItemRepository;

class WorkerItemService
{
    /**
     * Inisialisasi service dengan dependency repository.
     */
    public function __construct(
        private WorkerItemRepository $workerItemRepository
    ) {}

    /**
     * Mengambil seluruh item pekerjaan berdasarkan kategori.
     *
     * Catatan:
     * - Digunakan untuk kebutuhan detail kategori / dependent data
     */
    public function getWorkerItems($workerCategoryId)
    {
        return $this->workerItemRepository->getWorkerItems($workerCategoryId);
    }

    /**
     * Membuat item pekerjaan baru.
     *
     * Aturan bisnis:
     * - Nama pekerjaan harus unik di dalam kategori tersebut
     */
    public function createWorkerItem(array $data)
    {
        if ($this->workerItemRepository->existsByName(
            $data['work_name'],
            $data['category_id']
        )) {
            throw new DomainException('Nama pekerjaan sudah ada dalam kategori ini.');
        }

        return $this->workerItemRepository->create($data);
    }

    /**
     * Memperbarui item pekerjaan.
     *
     * Aturan bisnis:
     * - Nama pekerjaan harus tetap unik (kecuali data itu sendiri)
     */
    public function updateWorkerItem($id, array $data)
    {
        $workerItem = $this->workerItemRepository->find($id);

        if ($this->workerItemRepository->existsByNameExcept(
            $id,
            $data['work_name'],
            $data['category_id']
        )) {
            throw new DomainException('Nama pekerjaan sudah ada dalam kategori ini.');
        }

        return $this->workerItemRepository->update($workerItem, $data);
    }

    /**
     * Menghapus item pekerjaan.
     */
    public function deleteWorkerItem($id)
    {
        $workerItem = $this->workerItemRepository->find($id);

        return $this->workerItemRepository->delete($workerItem);
    }
}
