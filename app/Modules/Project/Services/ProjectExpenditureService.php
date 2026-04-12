<?php

namespace App\Modules\Project\Services;

use DomainException;
use App\Modules\Project\Repositories\ProjectExpenditureRepository;
use App\Modules\Rab\Services\RabService;

class ProjectExpenditureService
{
    /**
     * Inisialisasi service dengan dependency repository dan RAB service.
     *
     * Catatan:
     * - Repository digunakan untuk akses data expenditure
     * - RabService digunakan untuk menghitung total anggaran project
     */
    public function __construct(
        protected ProjectExpenditureRepository $projectExpenditureRepository,
        protected RabService $rabService
    ) {}

    /**
     * Mengambil detail project beserta ringkasan anggaran.
     *
     * Catatan:
     * - Mengambil project beserta daftar expenditure
     * - Menghitung total realisasi anggaran
     * - Menghitung sisa anggaran dan persentase penggunaan
     */
    public function getDetail(string $projectId): array
    {
        $project = $this->projectExpenditureRepository->getProjectDetail($projectId);

        $totalRealization = $this->projectExpenditureRepository->getTotalRealization($projectId);

        $totalBudget = $project->total_budget ?? 0;

        $remainingBudget = $totalBudget - $totalRealization;

        $percentage =
            $totalBudget > 0
            ? ($totalRealization / $totalBudget) * 100
            : 0;

        return [
            'project' => $project,
            'totalBudget' => $totalBudget,
            'totalRealization' => $totalRealization,
            'remainingBudget' => $remainingBudget,
            'percentage' => round($percentage, 2),
        ];
    }

    /**
     * Membuat data expenditure baru.
     *
     * Aturan bisnis:
     * - Nominal tidak boleh melebihi total anggaran project
     *
     * Catatan:
     * - Validasi anggaran dilakukan sebelum penyimpanan data
     */
    public function create(array $data)
    {
        $this->validateBudget(
            projectId: $data['project_id'],
            nominal: $data['nominal']
        );

        return $this->projectExpenditureRepository->create($data);
    }

    /**
     * Memperbarui data expenditure.
     *
     * Aturan bisnis:
     * - Nominal baru tidak boleh melebihi sisa anggaran
     *
     * Catatan:
     * - Menggunakan excludeId agar nominal lama tidak dihitung dua kali
     */
    public function update($id, array $data)
    {
        $projectExpenditure = $this->projectExpenditureRepository->find($id);

        $this->validateBudget(
            projectId: $projectExpenditure->project_id,
            nominal: $data['nominal'],
            excludeId: $id
        );

        $projectExpenditure->update($data);
    }

    /**
     * Menghapus data expenditure.
     *
     * Catatan:
     * - Tidak ada validasi tambahan
     * - Data dihapus secara langsung
     */
    public function delete($id)
    {
        $projectExpenditure = $this->projectExpenditureRepository->find($id);

        $projectExpenditure->delete();
    }

    /**
     * Mengambil total anggaran project dari RAB.
     *
     * Catatan:
     * - Menggunakan hasil generate RAB
     * - Mengambil nilai grand_total dari summary
     */
    private function getTotalBudget(string $projectId)
    {
        $rab = $this->rabService->generate($projectId);

        return $rab['summary']['grand_total'] ?? 0;
    }

    /**
     * Memvalidasi apakah nominal masih dalam batas anggaran.
     *
     * Aturan bisnis:
     * - Total realisasi tidak boleh melebihi total anggaran
     * - Jika update, nominal lama tidak dihitung ulang
     *
     * Catatan:
     * - Melempar DomainException jika anggaran tidak mencukupi
     */
    private function validateBudget(
        string $projectId,
        float $nominal,
        ?string $excludeId = null
    ) {

        $totalBudget = $this->getTotalBudget($projectId);

        if ($totalBudget <= 0) {
            throw new DomainException('Total anggaran tidak tersedia');
        }

        $currentTotal = $excludeId
            ? $this->projectExpenditureRepository
            ->getTotalRealizationExcept($projectId, $excludeId)
            : $this->projectExpenditureRepository
            ->getTotalRealization($projectId);

        $newTotal = $currentTotal + $nominal;

        if ($newTotal > $totalBudget) {
            $remaining = $totalBudget - $currentTotal;

            throw new DomainException(
                "Anggaran tidak mencukupi. Sisa: Rp " . number_format($remaining, 0, ',', '.')
            );
        }
    }
}
