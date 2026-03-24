<?php

namespace App\Modules\WorkerCategory\Repositories;

use App\Models\WorkerCategory;

class WorkerCategoryRepository
{
    public function getAll()
    {
        return WorkerCategory::query()
            ->with([
                'workerItems.workerCategory',
                'workerItems.ahsp',
                ])
            ->get();
    }

    public function find($id)
    {
        return WorkerCategory::findOrFail($id);
    }

    public function existsByNameExcept($id, string $name)
    {
        return WorkerCategory::where('id', '!=', $id)
            ->where('name', $name)
            ->exists();
    }

    public function existsByName(string $name)
    {
        return WorkerCategory::where('name', $name)->exists();
    }

    public function create(array $data)
    {
        return WorkerCategory::create($data);
    }

    public function update(WorkerCategory $workercategory, array $data)
    {
        return $workercategory->update($data);
    }

    public function delete(WorkerCategory $workercategory)
    {
        return $workercategory->delete();
    }
}
