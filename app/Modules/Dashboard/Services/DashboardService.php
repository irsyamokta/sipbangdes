<?php

namespace App\Modules\Dashboard\Services;

use App\Modules\Dashboard\Repositories\DashboardRepository;

class DashboardService
{
    /**
     * Inisialisasi service dengan dependency repository.
     */
    public function __construct(
        protected DashboardRepository $dashboardRepository
    ) {}

    /**
     * Mengambil seluruh data dashboard.
     *
     * Struktur data:
     * - summary: ringkasan angka utama
     * - rab_per_year: total RAB per tahun (approved saja)
     * - latest_projects: daftar project terbaru
     * - top_categories: kategori pekerjaan teratas
     */
    public function getDashboardData()
    {
        return [
            'summary' => $this->getSummary(),
            'rab_per_year' => $this->dashboardRepository->getApprovedRabPerYear(),
            'latest_projects' => $this->dashboardRepository->getLatestProjects(),
            'top_categories' => $this->dashboardRepository->getTopWorkerCategories(),
        ];
    }

    /**
     * Mengambil ringkasan data utama dashboard.
     *
     * Catatan:
     * - Digunakan untuk card statistik (total project, AHSP, dll)
     */
    private function getSummary()
    {
        return [
            'total_project' => $this->dashboardRepository->getTotalProject(),
            'active_project' => $this->dashboardRepository->getActiveProject(),
            'total_ahsp' => $this->dashboardRepository->getTotalAhsp(),
            'total_tos' => $this->dashboardRepository->getTotalTos(),
        ];
    }
}
