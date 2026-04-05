<?php

namespace App\Modules\Rab\Repositories;

use App\Models\Project;
use App\Models\TakeOffSheet;
use App\Models\RabComment;
use App\Models\User;

class RabRepository
{
    /**
     * Mengambil data project beserta seluruh relasi yang dibutuhkan untuk RAB.
     *
     * Relasi:
     * - TakeOffSheets (TOS)
     * - Worker Category
     * - AHSP beserta komponen:
     *   - Material
     *   - Upah
     *   - Alat
     * - Biaya operasional
     *
     * Catatan:
     * - Digunakan sebagai sumber utama perhitungan RAB
     * - Menggunakan eager loading untuk menghindari N+1 query
     */
    public function getProjectWithRelations(string $projectId)
    {
        return Project::with([
            'takeOffSheets.workerCategory',
            'takeOffSheets.ahsp.ahspComponentMaterials.masterMaterial',
            'takeOffSheets.ahsp.ahspComponentWages.masterWage',
            'takeOffSheets.ahsp.ahspComponentTools.masterTool',
            'operationalCosts',
        ])->findOrFail($projectId);
    }

    /**
     * Mengambil seluruh data Take Off Sheet (TOS) berdasarkan project.
     *
     * Relasi:
     * - AHSP
     * - Komponen material, upah, dan alat
     *
     * Catatan:
     * - Digunakan untuk kebutuhan perhitungan terpisah jika diperlukan
     */
    public function getTakeOffSheets(string $projectId)
    {
        return TakeOffSheet::with([
            'ahsp.ahspComponentMaterials.masterMaterial',
            'ahsp.ahspComponentWages.masterWage',
            'ahsp.ahspComponentTools.masterTool',
        ])
            ->where('project_id', $projectId)
            ->get();
    }

    /**
     * Mengambil seluruh komentar RAB berdasarkan project.
     *
     * Catatan:
     * - Data diurutkan dari yang terbaru
     * - Menyertakan relasi user untuk informasi pelaku aksi
     */
    public function getComments(string $projectId)
    {
        return RabComment::with('user')
            ->where('project_id', $projectId)
            ->latest()
            ->get();
    }

    /**
     * Menyimpan komentar baru ke dalam histori RAB.
     *
     * Digunakan untuk:
     * - Log aktivitas (send, review, approve, revision)
     * - Menyimpan catatan dari user
     */
    public function storeComment(array $data)
    {
        return RabComment::create($data);
    }

    /**
     * Memperbarui status RAB pada project.
     *
     * Contoh status:
     * - draft
     * - submitted
     * - reviewed
     * - revision
     * - approved
     */
    public function updateStatus(string $projectId, string $status)
    {
        return Project::where('id', $projectId)
            ->update(['rab_status' => $status]);
    }

    /**
     * Mengambil user dengan role approver.
     *
     * Catatan:
     * - Mengambil data terbaru (latest)
     * - Digunakan untuk penanggung jawab persetujuan RAB
     */
    public function getApprover()
    {
        return User::where('role', 'approver')->latest()->first();
    }
}
