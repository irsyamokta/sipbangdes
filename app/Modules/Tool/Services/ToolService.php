<?php

namespace App\Modules\Tool\Services;

use DomainException;
use App\Models\MasterTool;
use App\Modules\Tool\Repositories\ToolRepository;
use App\Services\CodeGeneratorService;
use Illuminate\Support\Facades\DB;

class ToolService
{
    public function __construct(
        protected ToolRepository $repo,
        protected CodeGeneratorService $codeGenerator
    ) {}

    public function getTools(?string $search = null)
    {
        return $this->repo->getAll($search);
    }

    public function createTool(array $data)
    {
        if ($this->repo->existsByName($data["name"]))
            throw new DomainException("Nama alat sudah ada");

        return DB::transaction(function () use ($data) {
            $data["code"] = $this->codeGenerator->generate(
                MasterTool::class,
                'code',
                'TL'
            );

            return $this->repo->create($data);
        });
    }

    public function updateTool($id, array $data)
    {
        $tool = $this->repo->find($id);

        if (!$tool) throw new DomainException("Alat tidak ditemukan");

        if ($this->repo->existsByNameExcept($id, $data["name"]))
            throw new DomainException("Nama alat sudah ada");

        return $this->repo->update($tool, $data);
    }

    public function deleteTool($id)
    {
        $tool = $this->repo->find($id);

        if (!$tool) throw new DomainException("Alat tidak ditemukan");

        return $this->repo->delete($tool);
    }
}
