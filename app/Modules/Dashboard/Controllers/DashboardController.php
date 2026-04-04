<?php

namespace App\Modules\Dashboard\Controllers;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Modules\Dashboard\Services\DashboardService;

class DashboardController extends Controller
{
    /**
     * Inisialisasi controller dengan dependency service.
     */
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    /**
     * Menampilkan halaman dashboard.
     *
     * Catatan:
     * - Seluruh data disiapkan oleh service
     * - Frontend hanya bertugas menerima dan menampilkan data
     */
    public function index()
    {
        return Inertia::render('Modules/Dashboard/Index', [
            'data' => $this->dashboardService->getDashboardData()
        ]);
    }
}
