<?php

namespace App\Http\Controllers\Dashboard;

use App\Services\Dashboard\DashboardService;
use Inertia\Inertia;

class DashboardController
{
    public function __construct(private DashboardService $dashboardService)
    {
    }

    public function index()
    {
        return Inertia::render('dashboard/index', $this->dashboardService->getStats());
    }
}
