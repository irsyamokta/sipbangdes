<?php

namespace App\Modules\Project\Services;

use App\Modules\Project\Repositories\ProgressRepository;
use App\Modules\Project\Repositories\ProjectExpenditureRepository;
use App\Modules\Rab\Services\RabService;

class ProjectDetailService
{
    /**
     * Inisialisasi service dengan dependency repository dan RAB service.
     *
     * Catatan:
     * - ProgressRepository digunakan untuk data progress project
     * - ProjectExpenditureRepository untuk data realisasi anggaran
     * - RabService untuk menghitung total anggaran project
     */
    public function __construct(
        protected ProgressRepository $progressRepository,
        protected ProjectExpenditureRepository $expenditureRepository,
        protected RabService $rabService
    ) {}

    /**
     * Mengambil detail lengkap project.
     *
     * Catatan:
     * - Mengambil data project beserta relasi progress
     * - Mengambil daftar expenditure terbaru
     * - Menghitung total progress fisik
     * - Menghitung total anggaran dari RAB
     * - Menghitung realisasi, sisa anggaran, dan persentase penggunaan
     */
    public function getDetail(string $projectId)
    {
        $project = $this->progressRepository->getProjectDetail($projectId);

        $totalProgress = $this->progressRepository->getTotalProgress($projectId);

        $expenditures = $project->projectExpenditures()->latest('date')->get();

        $totalRealization = $this->expenditureRepository->getTotalRealization($projectId);

        $rab = $this->rabService->generate($projectId);

        $totalBudget = $rab['summary']['grand_total'] ?? 0;

        $remainingBudget = $totalBudget - $totalRealization;

        $percentageBudget = $totalBudget > 0 ? ($totalRealization / $totalBudget) * 100 : 0;

        return [
            'project' => $project,

            'projectProgresses' => $project->projectProgresses,

            'totalProgress' => $totalProgress,

            'expenditures' => $expenditures,

            'totalBudget' => $totalBudget,

            'totalRealization' => $totalRealization,

            'remainingBudget' => $remainingBudget,

            'percentageBudget' => round($percentageBudget, 2),
        ];
    }
}
