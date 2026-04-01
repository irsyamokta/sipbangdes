<?php

namespace App\Modules\Project\Repositories;

use App\Models\Project;
use App\Models\ProjectProgress;

class ProgressRepository
{
    /**
     * Mengambil detail project beserta seluruh histori progress.
     *
     * Catatan:
     * - Progress diurutkan dari terbaru
     * - Relasi 'reportedBy' dan 'documents' di-load untuk kebutuhan tampilan detail
     * - Menggunakan findOrFail untuk memastikan data selalu ada
     */
    public function getProjectDetail(string $projectId)
    {
        return Project::with([
            'projectProgresses' => function ($query) {
                $query->latest();
            },
            'projectProgresses.reportedBy',
            'projectProgresses.documents',
        ])->findOrFail($projectId);
    }

    /**
     * Mengambil nilai progress terakhir dari project.
     *
     * Aturan:
     * - Progress terbaru dianggap sebagai total progress saat ini
     * - Jika belum ada progress, default = 0
     */
    public function getTotalProgress(string $projectId)
    {
        return ProjectProgress::where('project_id', $projectId)
            ->latest()
            ->value('percentage') ?? 0;
    }

    /**
     * Menyimpan data progress baru.
     *
     * Catatan:
     * - Tidak menangani relasi dokumen (ditangani di service layer)
     */
    public function createProgress(array $data)
    {
        return ProjectProgress::create($data);
    }
}
