<?php

namespace App\Modules\Dashboard\Controllers;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Modules\Dashboard\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $service
    ) {}

    public function index()
    {
        return Inertia::render('Modules/Dashboard/Index', [
            'data' => $this->service->getDashboardData()
        ]);
    }
}
