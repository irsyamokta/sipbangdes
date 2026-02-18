<?php

namespace App\Modules\Unit\Services;

use DomainException;
use App\Models\MasterUnit;
use App\Modules\Unit\Repositories\UnitRepository;
use App\Services\CodeGeneratorService;
use Illuminate\Support\Facades\DB;

class UnitService
{
    public function __construct(
        protected UnitRepository $repo,
        protected CodeGeneratorService $codeGenerator
    ) {}

    public function getUnits(?string $search = null)
    {
        return $this->repo->getAll($search);
    }

    public function createUnit(array $data)
    {
        if ($this->repo->existsByName($data["name"]))
            throw new DomainException("Nama satuan sudah ada");

        return DB::transaction(function () use ($data) {
            $data["code"] = $this->codeGenerator->generate(
                MasterUnit::class,
                'code',
                'SAT'
            );

            return $this->repo->create($data);
        });
    }

    public function updateUnit($id, array $data)
    {
        $unit = $this->repo->find($id);

        if (!$unit) throw new DomainException("Satuan tidak ditemukan");

        if ($this->repo->existsByNameExcept($id, $data["name"]))
            throw new DomainException("Nama satuan sudah ada");

        return $this->repo->update($unit, $data);
    }

    public function deleteUnit($id)
    {
        $unit = $this->repo->find($id);

        if (!$unit) throw new DomainException("Satuan tidak ditemukan");

        return $this->repo->delete($unit);
    }
}
