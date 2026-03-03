<?php

namespace App\Modules\Ahsp\Services;

use DomainException;
use App\Models\Ahsp;
use App\Modules\Ahsp\Repositories\AhspRepository;
use App\Services\CodeGeneratorService;
use Illuminate\Support\Facades\DB;

class AhspService {
    public function __construct(
        private AhspRepository $repo,
        private CodeGeneratorService $codeGeneratorService
    ) {}

    public function getAhsp(?string $search) {
        return $this->repo->getAll($search);
    }

    public function createAhsp(array $data){
        if ($this->repo->existsByName($data['work_name']))
            throw new DomainException('Nama pekerjaan sudah ada');

        return DB::transaction(function () use ($data) {
            $data['work_code'] = $this->codeGeneratorService->generateDotCode(
                Ahsp::class,
                'work_code',
                'A'
            );

            return $this->repo->create($data);
        });
    }

    public function updateAhsp($id, array $data){
        $ahsp = $this->repo->find($id);

        if ($this->repo->existsByNameExcept($id, $data['work_name']))
            throw new DomainException('Nama pekerjaan sudah ada');

        $ahsp->update($data);
    }

    public function deleteAhsp($id){
        $ahsp = $this->repo->find($id);

        $ahsp->delete();
    }
}
