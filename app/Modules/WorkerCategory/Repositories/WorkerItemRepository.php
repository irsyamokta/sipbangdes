<?php

namespace App\Modules\WorkerCategory\Repositories;

use App\Models\WorkerCategory;
use App\Models\WorkerItem;

class WorkerItemRepository
{
    public function getWorkerItems($workerCategoryId)
    {
        return WorkerCategory::findOrFail($workerCategoryId)
            ->workerItems()
            ->with('workerCategory')
            ->get();
    }

    public function find($id)
    {
        return WorkerItem::findOrFail($id);
    }

    public function create(array $data)
    {
        return WorkerItem::create($data);
    }

    public function update(WorkerItem $workerItem, array $data)
    {
        return $workerItem->update($data);
    }

    public function delete(WorkerItem $workerItem)
    {
        return $workerItem->delete();
    }
}
