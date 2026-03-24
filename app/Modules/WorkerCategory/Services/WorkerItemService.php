<?php

namespace App\Modules\WorkerCategory\Services;

use DomainException;
use App\Modules\WorkerCategory\Repositories\WorkerItemRepository;

class WorkerItemService
{
    public function __construct(
        private WorkerItemRepository $repo
    ) {}

    public function getWorkerItems($workerCategoryId)
    {
        return $this->repo->getWorkerItems($workerCategoryId);
    }

    public function createWorkerItem(array $data)
    {
        return $this->repo->create($data);
    }

    public function updateWorkerItem($id, array $data)
    {
        $workerItem = $this->repo->find($id);

        return $this->repo->update($workerItem, $data);
    }

    public function deleteWorkerItem($id)
    {
        $workerItem = $this->repo->find($id);

        return $this->repo->delete($workerItem);
    }
}
