<?php

namespace App\Modules\Ahsp\Services;

use DomainException;
use App\Models\Ahsp;
use App\Modules\Ahsp\Repositories\AhspToolRepository;

class AhspToolService
{
    public function __construct(
        protected AhspToolRepository $repo
    ) {}

    public function getAhspTools($ahspId)
    {
        return $this->repo->getAhspTools($ahspId);
    }

    public function createAhspTool(array $data)
    {
        if (!Ahsp::where('id', $data['ahsp_id'])->exists()) {
            throw new DomainException('AHSP tidak ditemukan');
        }

        return $this->repo->create($data);
    }

    public function updateAhspTool($id, array $data)
    {
        $ahspTool = $this->repo->find($id);

        return $this->repo->update($ahspTool, $data);
    }

    public function deleteAhspTool($id)
    {
        $ahspTool = $this->repo->find($id);

        return $this->repo->delete($ahspTool);
    }
}
