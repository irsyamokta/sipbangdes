<?php

namespace App\Modules\Wage\Services;

use DomainException;
use App\Models\MasterWage;
use App\Modules\Wage\Repositories\WageRepository;
use App\Services\CodeGeneratorService;
use Illuminate\Support\Facades\DB;

class WageService
{
    public function __construct(
        protected WageRepository $repo,
        protected CodeGeneratorService $codeGenerator
    ) {}

    public function getWages(?string $search = null)
    {
        return $this->repo->getAll($search);
    }

    public function createWage(array $data)
    {
        if ($this->repo->existsByPosition($data["position"]))
            throw new DomainException("Nama jabatan sudah ada");

        return DB::transaction(function () use ($data) {
            $data["code"] = $this->codeGenerator->generate(
                MasterWage::class,
                'code',
                'UPH'
            );

            return $this->repo->create($data);
        });
    }

    public function updateWage($id, array $data)
    {
        $wage = $this->repo->find($id);

        if (!$wage) throw new DomainException("Jabatan tidak ditemukan");

        if ($this->repo->existsByPositionExcept($id, $data["position"]))
            throw new DomainException("Nama jabatan sudah ada");

        return $this->repo->update($wage, $data);
    }

    public function deleteWage($id)
    {
        $wage = $this->repo->find($id);

        if (!$wage) throw new DomainException("Jabatan tidak ditemukan");

        return $this->repo->delete($wage);
    }
}
