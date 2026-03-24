<?php

namespace App\Modules\Wage\Repositories;

use App\Models\MasterWage;

class WageRepository
{
    public function getAll(?string $search = null)
    {
        return $this->baseQuery($search)->get();
    }

    public function getPaginated(?string $search = null, int $perPage = 10)
    {
        return $this->baseQuery($search)
            ->paginate($perPage)
            ->withQueryString();
    }

    private function baseQuery(?string $search)
    {
        return MasterWage::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                        ->orWhere('position', 'like', "%{$search}%");
                });
            });
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
