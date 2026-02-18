<?php

namespace App\Modules\Tool\Repositories;

use App\Models\MasterTool;

class ToolRepository
{
    public function getAll(?string $search = null)
    {
        return MasterTool::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            })
            ->paginate(10)
            ->withQueryString();
    }

    public function find($id): MasterTool
    {
        return MasterTool::findOrFail($id);
    }

    public function existsByNameExcept($id, string $name): bool
    {
        return MasterTool::where('name', $name)
            ->where('id', '!=', $id)
            ->exists();
    }

    public function existsByName(string $name): bool
    {
        return MasterTool::where('name', $name)->exists();
    }

    public function create(array $data): MasterTool
    {
        return MasterTool::create($data);
    }

    public function update(MasterTool $tool, array $data): MasterTool
    {
        $tool->update($data);
        return $tool;
    }

    public function delete(MasterTool $tool): bool
    {
        return $tool->delete();
    }
}
