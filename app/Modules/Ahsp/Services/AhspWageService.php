<?php

namespace App\Modules\Ahsp\Services;

use DomainException;
use App\Models\Ahsp;
use App\Modules\Ahsp\Repositories\AhspWageRepository;

class AhspWageService
{
    public function __construct(
        protected AhspWageRepository $repo
    ) {}

    public function getAhspWages($ahspId)
    {
        return $this->repo->getAhspWages($ahspId);
    }

    public function createAhspWage(array $data)
    {
        if (!Ahsp::where('id', $data['ahsp_id'])->exists()) {
            throw new DomainException('AHSP tidak ditemukan');
        }

        return $this->repo->create($data);
    }

    public function updateAhspWage($id, array $data)
    {
        $ahspWage = $this->repo->find($id);

        return $this->repo->update($ahspWage, $data);
    }

    public function deleteAhspWage($id)
    {
        $ahspWage = $this->repo->find($id);

        return $this->repo->delete($ahspWage);
    }
}
