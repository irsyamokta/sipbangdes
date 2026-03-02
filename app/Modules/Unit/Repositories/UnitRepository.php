<?php

namespace App\Modules\Unit\Repositories;

use App\Models\MasterUnit;

class UnitRepository
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
        return MasterUnit::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            });
    }

    public function find($id): MasterUnit
    {
        return MasterUnit::findOrFail($id);
    }

    public function existsByNameExcept($id, string $name): bool
    {
        return MasterUnit::where('name', $name)
            ->where('id', '!=', $id)
            ->exists();
    }

    public function existsByName(string $name): bool
    {
        return MasterUnit::where('name', $name)->exists();
    }

    public function create(array $data): MasterUnit
    {
        return MasterUnit::create($data);
    }

    public function update(MasterUnit $unit, array $data): MasterUnit
    {
        $unit->update($data);
        return $unit;
    }

    public function delete(MasterUnit $unit): bool
    {
        return $unit->delete();
    }
}
