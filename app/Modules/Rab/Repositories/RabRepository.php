<?php

namespace App\Modules\Rab\Repositories;

use App\Models\Project;
use App\Models\TakeOffSheet;
use App\Models\RabComment;
use App\Models\User;

class RabRepository
{
    public function getProjectWithRelations(string $projectId)
    {
        return Project::with([
            'takeOffSheets.workerCategory',
            'takeOffSheets.ahsp.ahspComponentMaterials.masterMaterial',
            'takeOffSheets.ahsp.ahspComponentWages.masterWage',
            'takeOffSheets.ahsp.ahspComponentTools.masterTool',
            'operationalCosts',
        ])->findOrFail($projectId);
    }

    public function getTakeOffSheets(string $projectId)
    {
        return TakeOffSheet::with([
            'ahsp.ahspComponentMaterials.masterMaterial',
            'ahsp.ahspComponentWages.masterWage',
            'ahsp.ahspComponentTools.masterTool',
        ])
            ->where('project_id', $projectId)
            ->get();
    }

    public function getComments(string $projectId)
    {
        return RabComment::with('user')
            ->where('project_id', $projectId)
            ->latest()
            ->get();
    }

    public function storeComment(array $data)
    {
        return RabComment::create($data);
    }

    public function updateStatus(string $projectId, string $status)
    {
        return Project::where('id', $projectId)
            ->update(['rab_status' => $status]);
    }

    public function getApprover()
    {
        return User::where('role', 'approver')->latest()->first();
    }
}
