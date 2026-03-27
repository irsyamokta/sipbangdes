<?php

namespace App\Modules\TakeOffSheet\Repositories;

use App\Models\TakeOffSheet;

class TakeOffSheetRepository
{
    public function getAll(?string $search = null, ?string $projectId = null)
    {
        return $this->baseQuery($search, $projectId)->get();
    }

    public function getPaginated(
        ?string $search = null,
        ?string $projectId = null,
        int $perPage = 10
    ) {
        return $this->baseQuery($search, $projectId)
            ->paginate($perPage)
            ->withQueryString();
    }

    private function baseQuery(?string $search, ?string $projectId)
    {
        return TakeOffSheet::query()
            ->with([
                'project',
                'ahsp',
                'workerCategory',
            ])

            ->when($projectId && $projectId !== 'all', function ($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })

            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('work_name', 'like', "%{$search}%");
                });
            })

            ->latest();
    }

    public function find($id)
    {
        return TakeOffSheet::with([
            'project',
            'ahsp',
            'workerCategory',
        ])->findOrFail($id);
    }

    public function existsByNameExcept(
        ?string $id,
        string $name,
        string $projectId,
        string $workerCategoryId
    ) {
        return TakeOffSheet::query()
            ->when($id, fn($q) => $q->where('id', '!=', $id))
            ->where('work_name', $name)
            ->where('project_id', $projectId)
            ->where('worker_category_id', $workerCategoryId)
            ->exists();
    }

    public function create(array $data)
    {
        return TakeOffSheet::create($data);
    }

    public function update(TakeOffSheet $tos, array $data)
    {
        $tos->update($data);
        return $tos;
    }

    public function delete(TakeOffSheet $tos)
    {
        return $tos->delete();
    }
}
