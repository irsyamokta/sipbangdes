<?php

namespace App\Modules\Project\Services;

use DomainException;
use App\Modules\Project\Repositories\ProjectRepository;
use App\Modules\Project\Repositories\ProgressRepository;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    public function __construct(
        protected ProjectRepository $repo,
        protected ProgressRepository $progress
    ) {}

    public function getProjects(?string $search = null, ?string $year = null)
    {   
        return $this->repo->getAll($search, $year);
    }

    public function createProject(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->repo->create($data);
        });
    }

    public function updateProject($id, array $data)
    {
        $project = $this->repo->find($id);

        if (!$project) throw new DomainException("Proyek tidak ditemukan");

        return $this->repo->update($project, $data);
    }

    public function deleteProject($id)
    {
        $project = $this->repo->find($id);

        if (!$project) throw new DomainException("Proyek tidak ditemukan");

        return $this->repo->delete($project);
    }
}
