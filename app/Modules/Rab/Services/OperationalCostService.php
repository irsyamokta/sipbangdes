<?php

namespace App\Modules\Rab\Services;

use DomainException;
use App\Models\Project;
use App\Modules\Rab\Repositories\OperationalCostRepository;

class OperationalCostService
{
    /**
     * Inisialisasi service dengan dependency repository.
     */
    public function __construct(
        protected OperationalCostRepository $operationalCostRepository
    ) {}

    /**
     * Menyimpan data biaya operasional baru.
     *
     * Aturan bisnis:
     * - Tidak bisa menambah biaya jika RAB sudah berstatus approved
     *
     * Proses:
     * - Validasi status project
     * - Menghitung total (volume x unit_price)
     * - Menyimpan data ke database
     */
    public function store(array $data)
    {
        $project = Project::findOrFail($data['project_id']);

        $this->ensureNotApproved($project);

        $data['total'] = $data['volume'] * $data['unit_price'];

        return $this->operationalCostRepository->create($data);
    }

    /**
     * Memperbarui data biaya operasional.
     *
     * Aturan bisnis:
     * - Tidak bisa mengubah data jika RAB sudah berstatus approved
     *
     * Proses:
     * - Ambil data operasional
     * - Validasi status project
     * - Hitung ulang total
     * - Update data
     */
    public function update($id, array $data)
    {
        $operationalCost = $this->operationalCostRepository->find($id);
        $project = $operationalCost->project;

        $this->ensureNotApproved($project);

        $data['total'] = $data['volume'] * $data['unit_price'];

        return $operationalCost->update($data);
    }

    /**
     * Menghapus data biaya operasional.
     *
     * Aturan bisnis:
     * - Tidak bisa menghapus data jika RAB sudah berstatus approved
     */
    public function destroy($id)
    {
        $operationalCost = $this->operationalCostRepository->find($id);
        $project = $operationalCost->project;

        $this->ensureNotApproved($project);

        return $operationalCost->delete();
    }

    /**
     * Memastikan project tidak berstatus approved.
     */
    private function ensureNotApproved($project)
    {
        if ($project->rab_status === 'approved') {
            throw new DomainException('RAB sudah disetujui, tidak bisa memodifikasi data.');
        }
    }
}
