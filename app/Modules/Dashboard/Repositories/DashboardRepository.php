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
     * Mengambil total nilai RAB project per tahun.
     *
     * Catatan:
     * - Hanya project dengan status RAB = approved
     * - Perhitungan menggunakan:
     *   - Snapshot TOS (material, upah, alat)
     *   - Biaya operasional
     * - Hasil dikelompokkan berdasarkan tahun anggaran
     */
    public function getApprovedRabPerYear()
    {
        $projects = Project::query()
            ->where('rab_status', 'approved')
            ->with([
                'takeOffSheets:id,project_id,volume,locked_snapshot,locked_at',
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
     * - Mengambil 3 project terakhir (latest)
     * - Menyertakan jumlah item TOS
     * - Menghitung subtotal menggunakan:
     *   - Snapshot (jika approved)
     *   - Data live (jika belum approved)
     *   - Biaya operasional
     */
    public function getLatestProjects()
    {
        return Project::query()
            ->withCount('takeOffSheets')
            ->with([
                'takeOffSheets:id,project_id,ahsp_id,volume,locked_snapshot,locked_at',
                'takeOffSheets.ahsp.ahspComponentMaterials.masterMaterial:id,price',
                'takeOffSheets.ahsp.ahspComponentWages.masterWage:id,price',
                'takeOffSheets.ahsp.ahspComponentTools.masterTool:id,price',
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
     * Mengambil 5 kategori pekerjaan dengan jumlah item terbanyak.
     *
     * Catatan:
     * - Berdasarkan jumlah relasi workerItems
     * - Digunakan untuk menampilkan top kategori
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
     * Menghitung total biaya suatu project.
     *
     * Komponen biaya:
     * - Material
     * - Upah
     * - Alat
     * - Biaya operasional
     *
     * Behavior:
     * - Jika project approved → gunakan snapshot
     * - Jika belum → gunakan data live (relasi AHSP)
     */
    private function calculateProjectTotal(Project $project): float
    {
        if ($project->rab_status === 'approved') {

            [$materialTotal, $wageTotal, $toolTotal] =
                $this->calculateFromSnapshots(
                    $project->takeOffSheets ?? collect()
                );
        } else {

            [$materialTotal, $wageTotal, $toolTotal] =
                $this->calculateFromLive(
                    $project->takeOffSheets ?? collect()
                );
        }

        $operationalTotal = ($project->operationalCosts ?? collect())
            ->sum(
                fn($op) => ($op->volume ?? 0) *
                    ($op->unit_price ?? 0)
            );

        return
            $materialTotal +
            $wageTotal +
            $toolTotal +
            $operationalTotal;
    }

    /**
     * Menghitung total biaya dari snapshot TOS.
     *
     * Catatan:
     * - Snapshot bersifat immutable (tidak berubah)
     * - Digunakan untuk menjaga konsistensi perhitungan setelah approved
     * - Wajib tersedia untuk setiap TOS
     *
     * Exception:
     * - Akan melempar error jika snapshot tidak ditemukan
     */
    private function calculateFromSnapshots(Collection $tosList)
    {
        $recapMaterial = [];
        $recapWage = [];
        $recapTool = [];

        foreach ($tosList as $tos) {

            if (!$tos->locked_snapshot) {
                throw new \Exception(
                    "Snapshot missing on approved project: {$tos->id}"
                );
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
        }

        return [
            $this->calculateTotal($recapMaterial),
            $this->calculateTotal($recapWage),
            $this->calculateTotal($recapTool),
        ];
    }

    /**
     * Menghitung total biaya dari data live (relasi AHSP).
     *
     * Catatan:
     * - Digunakan untuk project yang belum approved
     * - Perhitungan berdasarkan:
     *   qty = coefficient × volume TOS
     *   total = qty × harga master
     */
    private function calculateFromLive(Collection $tosList)
    {
        $recapMaterial = [];
        $recapWage = [];
        $recapTool = [];

        foreach ($tosList as $tos) {

            $ahsp = $tos->ahsp;
            if (!$ahsp) continue;

            $volume = $tos->volume;

            foreach ($ahsp->ahspComponentMaterials as $item) {
                $qty = $item->coefficient * $volume;

                $price = $item->masterMaterial->price ?? 0;

                $this->accumulateLive(
                    $recapMaterial,
                    $item->material_id,
                    $price,
                    $qty
                );
            }

            foreach ($ahsp->ahspComponentWages as $item) {
                $qty = $item->coefficient * $volume;

                $price = $item->masterWage->price ?? 0;

                $this->accumulateLive(
                    $recapWage,
                    $item->wage_id,
                    $price,
                    $qty
                );
            }

            foreach ($ahsp->ahspComponentTools as $item) {
                $qty = $item->coefficient * $volume;

                $price = $item->masterTool->price ?? 0;

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
     * Mengakumulasi data dari snapshot.
     *
     * Behavior:
     * - Mengelompokkan berdasarkan ID item
     * - Qty dijumlahkan
     * - Price diambil dari snapshot (tidak berubah)
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
     * Mengakumulasi data dari perhitungan live.
     *
     * Behavior:
     * - Mengelompokkan berdasarkan ID item
     * - Qty dijumlahkan
     * - Price mengikuti harga master terbaru
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
     * Menghitung total biaya dari kumpulan item.
     *
     * Rumus:
     * - total = ceil(qty) × price
     *
     * Catatan:
     * - Qty dibulatkan ke atas untuk menghindari kekurangan material
     */
    private function calculateTotal(array $items)
    {
        return collect($items)
            ->sum(fn($item) => ceil($item['qty']) * $item['price']);
    }
}
