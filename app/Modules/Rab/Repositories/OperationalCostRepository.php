<?php

namespace App\Modules\Rab\Repositories;

use App\Models\OperationalCost;

class OperationalCostRepository
{
    public function getByProject(string $projectId)
    {
        return OperationalCost::where('project_id', $projectId)->get();
    }

    public function find(string $id)
    {
        return OperationalCost::findOrFail($id);
    }

    public function create(array $data)
    {
        return OperationalCost::create($data);
    }

    public function update(OperationalCost $operationalCost, array $data)
    {
        return $operationalCost->update($data);
    }

    public function delete(OperationalCost $operationalCost)
    {
        return $operationalCost->delete();
    }
}
