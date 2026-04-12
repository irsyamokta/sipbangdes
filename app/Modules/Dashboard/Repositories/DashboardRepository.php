<?php

namespace App\Modules\Dashboard\Repositories;

use App\Models\Project;
use App\Models\Ahsp;
use App\Models\TakeOffSheet;
use App\Models\WorkerCategory;
use Illuminate\Support\Collection;

class DashboardRepository
{
    /**
     * Mengambil total RAB project per tahun (hanya yang approved).
     *
     * Catatan:
     * - Menggunakan snapshot TOS untuk menghitung material, upah, dan alat
     * - Ditambah biaya operasional
     * - Hasil dikelompokkan berdasarkan tahun anggaran
     */
    public function getApprovedRabPerYear()
    {
        $projects = Project::query()
            ->where('rab_status', 'approved')
            ->with([
                'takeOffSheets:id,project_id,ahsp_id,volume,locked_snapshot',
                'takeOffSheets.ahsp.ahspComponentMaterials.masterMaterial',
                'takeOffSheets.ahsp.ahspComponentWages.masterWage',
                'takeOffSheets.ahsp.ahspComponentTools.masterTool',
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

    /**
     * Mengambil total seluruh project.
     */
    public function getTotalProject()
    {
        return Project::count();
    }

    /**
     * Mengambil jumlah project aktif.
     *
     * Catatan:
     * - Status 'berjalan' dianggap sebagai aktif
     */
    public function getActiveProject()
    {
        return Project::where('project_status', 'berjalan')->count();
    }

    /**
     * Mengambil total data AHSP.
     */
    public function getTotalAhsp()
    {
        return Ahsp::count();
    }

    /**
     * Mengambil total data Take Off Sheet (TOS).
     */
    public function getTotalTos()
    {
        return TakeOffSheet::count();
    }

    /**
     * Mengambil daftar project terbaru.
     *
     * Catatan:
     * - Mengambil 3 project terakhir
     * - Menghitung subtotal menggunakan snapshot + biaya operasional
     */
    public function getLatestProjects()
    {
        return Project::query()
            ->withCount('takeOffSheets')
            ->with([
                'takeOffSheets:id,project_id,ahsp_id,volume,locked_snapshot',
                'takeOffSheets.ahsp.ahspComponentMaterials.masterMaterial',
                'takeOffSheets.ahsp.ahspComponentWages.masterWage',
                'takeOffSheets.ahsp.ahspComponentTools.masterTool',
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

    /**
     * Mengambil 5 kategori pekerjaan teratas.
     *
     * Catatan:
     * - Digunakan menampilkan top 5 kategori
     */
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

    /**
     * Menghitung total biaya project.
     *
     * Komponen:
     * - Material
     * - Upah
     * - Alat
     * - Biaya operasional
     */
    private function calculateProjectTotal(Project $project): float
    {
        [$materialTotal, $wageTotal, $toolTotal] =
            $this->calculateFromSources($project->takeOffSheets ?? collect());

        $operationalTotal = ($project->operationalCosts ?? collect())
            ->sum(function ($op) {
                return ($op->volume ?? 0) * ($op->unit_price ?? 0);
            });

        return $materialTotal + $wageTotal + $toolTotal + $operationalTotal;
    }

    /**
     * Menghitung total dari berbagai sumber TOS.
     *
     * Catatan:
     * - Menghitung total material, upah, dan alat
     * - Snapshot digunakan agar perhitungan tidak berubah walaupun data master berubah
     */
    private function calculateFromSources(Collection $tosList)
    {
        $recapMaterial = [];
        $recapWage = [];
        $recapTool = [];

        foreach ($tosList as $tos) {

            if ($tos->isLocked()) {

                if (!$tos->locked_snapshot) {
                    continue;
                }

                $snapshot = $tos->locked_snapshot;

                $this->accumulate(
                    $recapMaterial,
                    $snapshot['materials'] ?? []
                );

                $this->accumulate(
                    $recapWage,
                    $snapshot['wages'] ?? []
                );

                $this->accumulate(
                    $recapTool,
                    $snapshot['tools'] ?? []
                );

                continue;
            }

            $ahsp = $tos->ahsp;
            if (!$ahsp) continue;

            $volume = $tos->volume;

            // MATERIAL
            foreach ($ahsp->ahspComponentMaterials as $item) {

                $qty =
                    $item->coefficient *
                    $volume;

                $price =
                    $item->masterMaterial->price ?? 0;

                $this->accumulateLive(
                    $recapMaterial,
                    $item->material_id,
                    $price,
                    $qty
                );
            }

            // WAGE
            foreach ($ahsp->ahspComponentWages as $item) {

                $qty =
                    $item->coefficient *
                    $volume;

                $price =
                    $item->masterWage->price ?? 0;

                $this->accumulateLive(
                    $recapWage,
                    $item->wage_id,
                    $price,
                    $qty
                );
            }

            // TOOL
            foreach ($ahsp->ahspComponentTools as $item) {

                $qty =
                    $item->coefficient *
                    $volume;

                $price =
                    $item->masterTool->price ?? 0;

                $this->accumulateLive(
                    $recapTool,
                    $item->tool_id,
                    $price,
                    $qty
                );
            }
        }

        return [
            $this->calculateTotal($recapMaterial),
            $this->calculateTotal($recapWage),
            $this->calculateTotal($recapTool),
        ];
    }

    /**
     * Mengakumulasi item berdasarkan ID.
     *
     * Catatan:
     * - Qty dijumlahkan
     * - Price diambil dari snapshot
     */
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

    /**
     * Summary of accumulateLive
     * @param array $target
     * @param mixed $key
     * @param mixed $price
     * @param mixed $qty
     * @return void
     */
    private function accumulateLive(
        array &$target,
        $key,
        $price,
        $qty
    ) {
        if (!isset($target[$key])) {
            $target[$key] = [
                'price' => $price,
                'qty' => 0,
            ];
        }

        $target[$key]['qty'] += $qty;
    }

    /**
     * Menghitung total biaya dari item.
     *
     * Catatan:
     * - Qty dibulatkan ke atas (ceil)
     */
    private function calculateTotal(array $items)
    {
        return collect($items)
            ->sum(fn($item) => ceil($item['qty']) * $item['price']);
    }
}
