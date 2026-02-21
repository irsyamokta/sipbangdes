<?php

namespace App\Modules\Project\Repositories;

use App\Models\Project;
use App\Models\ProjectProgress;

class ProgressRepository
{
    public function getProjectDetail(string $projectId): Project
    {
        return Project::with([
            'projectProgresses' => function ($query) {
                $query->latest();
            },
            'projectProgresses.reportedBy',
            'projectProgresses.documents',
        ])->findOrFail($projectId);
    }

    public function getTotalProgress(string $projectId): float
    {
        return ProjectProgress::where('project_id', $projectId)
            ->latest()
            ->value('percentage') ?? 0;
    }

    public function createProgress(array $data): ProjectProgress
    {
        return ProjectProgress::create($data);
    }
}
