<?php

namespace App\Modules\Ahsp\Repositories;

use App\Models\Ahsp;
use App\Models\AhspComponentMaterial;

class AhspMaterialRepository
{
    public function getAhspMaterials($ahspId)
    {
        return Ahsp::findOrFail($ahspId)
            ->ahspComponentMaterials()
            ->with('masterMaterial')
            ->get();
    }

    public function find($id)
    {
        return AhspComponentMaterial::findOrFail($id);
    }

    public function create(array $data)
    {
        return AhspComponentMaterial::create($data);
    }

    public function update(AhspComponentMaterial $ahspMaterial, array $data)
    {
        return $ahspMaterial->update($data);
    }

    public function delete(AhspComponentMaterial $ahspMaterial)
    {
        return $ahspMaterial->delete();
    }
}
