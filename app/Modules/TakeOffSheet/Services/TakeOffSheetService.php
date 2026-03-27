<?php

namespace App\Modules\TakeOffSheet\Services;

use DomainException;
use Illuminate\Support\Facades\DB;
use App\Modules\TakeOffSheet\Repositories\TakeOffSheetRepository;

class TakeOffSheetService
{
    public function __construct(
        protected TakeOffSheetRepository $repo
    ) {}

    public function getTakeOffSheets(?string $search = null, ?string $projectId = null)
    {
        return $this->repo->getPaginated($search, $projectId);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {

            if ($this->repo->existsByNameExcept(
                null,
                $data['work_name'],
                $data['project_id'],
                $data['worker_category_id']
            )) {
                throw new DomainException('Nama pekerjaan sudah digunakan pada proyek & kategori ini');
            }

            return $this->repo->create($data);
        });
    }

    public function update(string $id, array $data)
    {
        $tos = $this->repo->find($id);

        if ($this->repo->existsByNameExcept(
            $id,
            $data['work_name'],
            $data['project_id'],
            $data['worker_category_id']
        )) {
            throw new DomainException('Nama pekerjaan sudah digunakan pada proyek & kategori ini');
        }
        
        return DB::transaction(function () use ($tos, $data) {
            return $this->repo->update($tos, $data);
        });
    }

    public function delete(string $id)
    {
        $tos = $this->repo->find($id);

        return $this->repo->delete($tos);
    }
}
