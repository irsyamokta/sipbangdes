<?php

namespace App\Modules\Material\Services;

use DomainException;
use App\Models\MasterMaterial;
use App\Modules\Material\Repositories\MaterialRepository;
use App\Services\CodeGeneratorService;
use Illuminate\Support\Facades\DB;

class MaterialService
{
    public function __construct(
        protected MaterialRepository $repo,
        protected CodeGeneratorService $codeGenerator
    ) {}

    public function getMaterials(
        ?string $search = null,
        bool $paginate = true,
        int $perPage = 10
    ) {
        if ($paginate) {
            return $this->repo->getPaginated($search, $perPage);
        }

        return $this->repo->getAll($search);
    }

    public function createMaterial(array $data)
    {
        if ($this->repo->existsByName($data["name"]))
            throw new DomainException("Nama material sudah ada");

        return DB::transaction(function () use ($data) {
            $data["code"] = $this->codeGenerator->generate(
                MasterMaterial::class,
                'code',
                'MAT'
            );

            return $this->repo->create($data);
        });
    }

    public function updateMaterial($id, array $data)
    {
        $material = $this->repo->find($id);

        if ($this->repo->existsByNameExcept($id, $data["name"]))
            throw new DomainException("Nama material sudah ada");

        return $this->repo->update($material, $data);
    }

    public function deleteMaterial($id)
    {
        $material = $this->repo->find($id);

        return $this->repo->delete($material);
    }
}
