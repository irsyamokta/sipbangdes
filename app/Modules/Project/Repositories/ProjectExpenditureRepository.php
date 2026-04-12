<?php

namespace App\Modules\Project\Repositories;

use App\Models\Project;
use App\Models\ProjectExpenditure;

class ProjectExpenditureRepository
{
    /**
     * Mengambil detail project beserta seluruh data realisasi anggaran.
     *
     * Catatan:
     * - Expenditure diurutkan dari tanggal terbaru
     * - Digunakan untuk halaman detail project
     */
    public function getProjectDetail(string $projectId)
    {
        return Project::with([
            'projectExpenditures' => function ($query) {
                $query->latest('date');
            }
        ])->findOrFail($projectId);
    }

    /**
     * Mengambil total realisasi anggaran berdasarkan project.
     *
     * Catatan:
     * - Menggunakan agregasi sum pada kolom nominal
     */
    public function getTotalRealization(string $projectId)
    {
        return ProjectExpenditure::where('project_id', $projectId)
            ->sum('nominal');
    }

    /**
     * Mengambil total realisasi anggaran dengan mengecualikan data tertentu.
     *
     * Digunakan untuk:
     * - Proses update expenditure
     *
     * Catatan:
     * - Menghindari perhitungan ganda pada nominal lama
     */
    public function getTotalRealizationExcept(string $projectId, string $excludeId)
    {
        return ProjectExpenditure::query()
            ->where('project_id', $projectId)
            ->where('id', '!=', $excludeId)
            ->sum('nominal');
    }

    /**
     * Mengambil satu data expenditure berdasarkan ID.
     *
     * Catatan:
     * - Menggunakan findOrFail untuk memastikan data tersedia
     */
    public function find($id)
    {
        return ProjectExpenditure::findOrFail($id);
    }

    /**
     * Menyimpan data expenditure baru.
     *
     * Catatan:
     * - Validasi dilakukan di layer service
     */
    public function create(array $data)
    {
        return ProjectExpenditure::create($data);
    }

    /**
     * Memperbarui data expenditure yang ada.
     */
    public function update(ProjectExpenditure $expenditure, array $data)
    {
        $expenditure->update($data);
        return $expenditure;
    }

    /**
     * Menghapus data expenditure dari database.
     */
    public function delete(ProjectExpenditure $expenditure)
    {
        return $expenditure->delete();
    }
}
