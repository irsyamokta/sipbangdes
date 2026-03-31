<?php

namespace App\Modules\Rab\Services;

use DomainException;
use App\Models\Project;
use App\Modules\Rab\Repositories\OperationalCostRepository;

class OperationalCostService
{
    public function __construct(
        protected OperationalCostRepository $repo
    ) {}

    public function store(array $data)
    {
        $project = Project::findOrFail($data['project_id']);

        if ($project->rab_status === 'approved') {
            throw new DomainException('RAB sudah disetujui, tidak bisa menambah biaya');
        }

        $data['total'] = $data['volume'] * $data['unit_price'];

        return $this->repo->create($data);
    }

    public function update($id, array $data)
    {
        $operationalCost = $this->repo->find($id);
        $project = $operationalCost->project;

        if ($project->rab_status === 'approved') {
            throw new DomainException('RAB sudah disetujui, tidak bisa mengubah data');
        }

        $data['total'] = $data['volume'] * $data['unit_price'];

        return $operationalCost->update($data);
    }

    public function destroy($id)
    {
        $operationalCost = $this->repo->find($id);
        $project = $operationalCost->project;

        if ($project->rab_status === 'approved') {
            throw new DomainException('RAB sudah disetujui, tidak bisa menghapus data');
        }

        return $operationalCost->delete();
    }
}
