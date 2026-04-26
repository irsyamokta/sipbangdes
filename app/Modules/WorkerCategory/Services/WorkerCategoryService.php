<?php

namespace App\Modules\WorkerCategory\Services;

use DomainException;
use App\Modules\WorkerCategory\Repositories\WorkerCategoryRepository;

class WorkerCategoryService
{
    /**
     * Inisialisasi service dengan dependency repository.
     */
    public function __construct(
        private WorkerCategoryRepository $workerCategoryRepository
    ) {}

    /**
     * Mengambil seluruh data kategori pekerjaan.
     *
     * Catatan:
     * - Digunakan untuk kebutuhan tampilan (index / dropdown)
     */
    public function getWorkerCategory(
        ?string $search = null,
        int|string $perPage = 10
    ) {
        return $this->workerCategoryRepository->getPaginated($search, $perPage);
    }

    /**
     * Membuat kategori pekerjaan baru.
     *
     * Aturan bisnis:
     * - Nama kategori harus unik
     */
    public function createWorkerCategory(array $data)
    {
        if ($this->workerCategoryRepository->existsByName($data["name"]))
            throw new DomainException("Kategori pekerjaan sudah ada.");

        return $this->workerCategoryRepository->create($data);
    }

    /**
     * Memperbarui data kategori pekerjaan.
     *
     * Aturan bisnis:
     * - Nama kategori harus tetap unik (kecuali data itu sendiri)
     */
    public function updateWorkerCategory($id, array $data)
    {
        $workerCategory = $this->workerCategoryRepository->find($id);

        if ($this->workerCategoryRepository->existsByNameExcept($id, $data["name"]))
            throw new DomainException("Kategori pekerjaan sudah ada.");

        return $this->workerCategoryRepository->update($workerCategory, $data);
    }

    /**
     * Menghapus kategori pekerjaan.
     */
    public function deleteWorkerCategory($id)
    {
        $workerCategory = $this->workerCategoryRepository->find($id);

        return $this->workerCategoryRepository->delete($workerCategory);
    }
}
