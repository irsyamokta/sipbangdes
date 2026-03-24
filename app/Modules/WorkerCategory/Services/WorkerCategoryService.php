<?php

namespace App\Modules\WorkerCategory\Services;

use DomainException;
use App\Modules\WorkerCategory\Repositories\WorkerCategoryRepository;

class WorkerCategoryService
{
    public function __construct(
        private WorkerCategoryRepository $repo
    ) {}

    public function getWorkerCategory()
    {
        return $this->repo->getAll();
    }

    public function createWorkerCategory(array $data)
    {
        if ($this->repo->existsByName($data["name"]))
            throw new DomainException("Kategori pekerjaan sudah ada");

        return $this->repo->create($data);
    }

    public function updateWorkerCategory($id, array $data)
    {
        $workerCategory = $this->repo->find($id);

        if ($this->repo->existsByNameExcept($id, $data["name"]))
            throw new DomainException("Kategori pekerjaan sudah ada");

        return $this->repo->update($workerCategory, $data);
    }

    public function deleteWorkerCategory($id)
    {
        $workerCategory = $this->repo->find($id);

        return $this->repo->delete($workerCategory);
    }
}
