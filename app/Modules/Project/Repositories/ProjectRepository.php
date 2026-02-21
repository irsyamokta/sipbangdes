<?php

namespace App\Modules\Project\Repositories;

use App\Models\Project;

class ProjectRepository
{
    public function getAll(?string $search = null, ?string $year = null)
    {
        return Project::query()
            ->with('latestProgress')
            ->when($search, function ($query) use ($search) {
                $query->where('project_name', 'like', "%{$search}%");
            })
            ->when($year, function ($query) use ($year) {
                $query->where('budget_year', $year);
            })
            ->latest()
            ->get();
    }

    public function find($id): Project
    {
        return Project::find($id);
    }

    public function create(array $data): Project
    {
        return Project::create($data);
    }

    public function update(Project $project, array $data): Project
    {
        $project->update($data);
        return $project;
    }

    public function delete(Project $project): bool
    {
        return $project->delete();
    }
}
