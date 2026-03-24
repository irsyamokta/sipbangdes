<?php

namespace App\Modules\Ahsp\Services;

use DomainException;
use App\Models\Ahsp;
use App\Modules\Ahsp\Repositories\AhspMaterialRepository;

class AhspMaterialService
{
    public function __construct(
        protected AhspMaterialRepository $repo
    ) {}

    public function getAhspMaterials($ahspId)
    {
        return $this->repo->getAhspMaterials($ahspId);
    }

    public function createAhspMaterial(array $data)
    {
        if (!Ahsp::where('id', $data['ahsp_id'])->exists()) {
            throw new DomainException('AHSP tidak ditemukan');
        }

        return $this->repo->create($data);
    }

    public function updateAhspMaterial($id, array $data)
    {
        $ahspMaterial = $this->repo->find($id);

        return $this->repo->update($ahspMaterial, $data);
    }

    public function deleteAhspMaterial($id)
    {
        $ahspMaterial = $this->repo->find($id);

        return $this->repo->delete($ahspMaterial);
    }
}
