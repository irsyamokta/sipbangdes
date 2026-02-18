<?php

namespace App\Modules\Wage\Repositories;

use App\Models\MasterWage;

class WageRepository
{
    public function getAll(?string $search = null)
    {
        return MasterWage::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                        ->orWhere('position', 'like', "%{$search}%");
                });
            })
            ->paginate(10)
            ->withQueryString();
    }

    public function find($id): MasterWage
    {
        return MasterWage::findOrFail($id);
    }

    public function existsByPositionExcept($id, string $position): bool
    {
        return MasterWage::where('position', $position)
            ->where('id', '!=', $id)
            ->exists();
    }

    public function existsByPosition(string $position): bool
    {
        return MasterWage::where('position', $position)->exists();
    }

    public function create(array $data): MasterWage
    {
        return MasterWage::create($data);
    }

    public function update(MasterWage $wage, array $data): MasterWage
    {
        $wage->update($data);
        return $wage;
    }

    public function delete(MasterWage $wage): bool
    {
        return $wage->delete();
    }
}
