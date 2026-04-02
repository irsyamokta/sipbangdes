<?php

namespace App\Modules\Ahsp\Repositories;

use App\Models\Ahsp;

class AhspRepository
{
    public function getAll(?string $search)
    {
        return Ahsp::query()
            ->with([
                'ahspComponentMaterials.masterMaterial',
                'ahspComponentWages.masterWage',
                'ahspComponentTools.masterTool',
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('work_code', 'like', "%{$search}%")
                        ->orWhere('work_name', 'like', "%{$search}%");
                });
            })
            ->get();
    }

    public function getByCategory(?string $categoryId = null)
    {
        return Ahsp::query()
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('worker_category_id', $categoryId);
            })
            ->get();
    }

    public function find($id)
    {
        return Ahsp::findOrFail($id);
    }

    public function existsByNameExcept($id, string $name)
    {
        return Ahsp::where('id', '!=', $id)
            ->where('work_name', $name)
            ->exists();
    }

    public function existsByName(string $name)
    {
        return Ahsp::where('work_name', $name)->exists();
    }

    public function create(array $data)
    {
        return Ahsp::create($data);
    }

    public function update(Ahsp $ahsp, array $data)
    {
        return $ahsp->update($data);
    }

    public function delete(Ahsp $ahsp)
    {
        return $ahsp->delete();
    }
}
