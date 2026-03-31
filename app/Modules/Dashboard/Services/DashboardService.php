<?php

namespace App\Modules\Dashboard\Services;

use App\Modules\Dashboard\Repositories\DashboardRepository;

class DashboardService
{
    public function __construct(
        protected DashboardRepository $repo
    ) {}

    public function getDashboardData(): array
    {
        return [
            'summary' => $this->getSummary(),
            'rab_per_year' => $this->repo->getApprovedRabPerYear(),
            'latest_projects' => $this->repo->getLatestProjects(),
            'top_categories' => $this->repo->getTopWorkerCategories(),
        ];
    }

    private function getSummary()
    {
        return [
            'total_project' => $this->repo->getTotalProject(),
            'active_project' => $this->repo->getActiveProject(),
            'total_ahsp' => $this->repo->getTotalAhsp(),
            'total_tos' => $this->repo->getTotalTos(),
        ];
    }
}
