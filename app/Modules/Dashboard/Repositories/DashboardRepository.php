<?php

namespace App\Modules\Dashboard\Repositories;

use App\Models\Project;
use App\Models\Ahsp;
use App\Models\TakeOffSheet;
use App\Models\WorkerCategory;
use Illuminate\Support\Collection;

class DashboardRepository
{
    public function getApprovedRabPerYear()
    {
        $projects = Project::query()
            ->where('rab_status', 'approved')
            ->with([
                'takeOffSheets:id,project_id,locked_snapshot',
                'operationalCosts:id,project_id,volume,unit_price'
            ])
            ->get();

        return $projects
            ->groupBy('budget_year')
            ->map(function ($projects, $year) {

                $totalRab = $projects->sum(function ($project) {
                    return $this->calculateProjectTotal($project);
                });

                return (object) [
                    'year' => $year,
                    'total_rab' => $totalRab,
                    'total_project' => $projects->count(),
                ];
            })
            ->values();
    }

    public function getTotalProject()
    {
        return Project::count();
    }

    public function getActiveProject()
    {
        return Project::where('project_status', 'berjalan')->count();
    }

    public function getTotalAhsp()
    {
        return Ahsp::count();
    }

    public function getTotalTos()
    {
        return TakeOffSheet::count();
    }

    public function getLatestProjects()
    {
        return Project::query()
            ->withCount('takeOffSheets')
            ->with([
                'takeOffSheets:id,project_id,locked_snapshot',
                'operationalCosts:id,project_id,volume,unit_price'
            ])
            ->latest()
            ->limit(3)
            ->get()
            ->map(function (Project $project) {

                return (object) [
                    'id' => $project->id,
                    'project_name' => $project->project_name,
                    'location' => $project->location,
                    'budget_year' => $project->budget_year,
                    'total_items' => $project->take_off_sheets_count,
                    'status' => $project->project_status,
                    'subtotal' => $this->calculateProjectTotal($project),
                ];
            });
    }

    public function getTopWorkerCategories()
    {
        return WorkerCategory::query()
            ->withCount('workerItems')
            ->orderByDesc('worker_items_count')
            ->limit(5)
            ->get()
            ->map(fn($item) => [
                'name' => $item->name,
                'total_items' => $item->worker_items_count,
            ]);
    }

    private function calculateProjectTotal(Project $project): float
    {
        [$materialTotal, $wageTotal, $toolTotal] =
            $this->calculateFromSnapshots($project->takeOffSheets ?? collect());

        $operationalTotal = ($project->operationalCosts ?? collect())
            ->sum(function ($op) {
                return ($op->volume ?? 0) * ($op->unit_price ?? 0);
            });

        return $materialTotal + $wageTotal + $toolTotal + $operationalTotal;
    }

    private function calculateFromSnapshots(Collection $tosList)
    {
        $recapMaterial = [];
        $recapWage = [];
        $recapTool = [];

        foreach ($tosList as $tos) {

            if (!$tos->locked_snapshot) continue;

            $snapshot = $tos->locked_snapshot;

            $this->accumulate($recapMaterial, $snapshot['materials'] ?? []);
            $this->accumulate($recapWage, $snapshot['wages'] ?? []);
            $this->accumulate($recapTool, $snapshot['tools'] ?? []);
        }

        return [
            $this->calculateTotal($recapMaterial),
            $this->calculateTotal($recapWage),
            $this->calculateTotal($recapTool),
        ];
    }

    private function accumulate(array &$target, array $items)
    {
        foreach ($items as $item) {
            $key = $item['id'];

            if (!isset($target[$key])) {
                $target[$key] = [
                    'price' => $item['price'],
                    'qty' => 0,
                ];
            }

            $target[$key]['qty'] += $item['qty'];
        }
    }

    private function calculateTotal(array $items)
    {
        return collect($items)
            ->sum(fn($item) => ceil($item['qty']) * $item['price']);
    }
}
