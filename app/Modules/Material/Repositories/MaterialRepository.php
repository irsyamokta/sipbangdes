<?php

namespace App\Modules\Material\Repositories;

use App\Models\MasterMaterial;

class MaterialRepository
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
        return MasterMaterial::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            });
    }

    public function find($id): MasterMaterial
    {
        return MasterMaterial::findOrFail($id);
    }

    public function existsByNameExcept($id, string $name): bool
    {
        return MasterMaterial::where('name', $name)
            ->where('id', '!=', $id)
            ->exists();
    }

    public function existsByName(string $name): bool
    {
        return MasterMaterial::where('name', $name)->exists();
    }

    public function create(array $data): MasterMaterial
    {
        return MasterMaterial::create($data);
    }

    public function update(MasterMaterial $material, array $data): MasterMaterial
    {
        $material->update($data);
        return $material;
    }

    public function delete(MasterMaterial $material): bool
    {
        return $material->delete();
    }
}
