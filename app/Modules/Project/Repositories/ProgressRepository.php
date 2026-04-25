<?php

namespace App\Modules\Project\Repositories;

use App\Models\Project;
use App\Models\ProjectDocument;
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

    /**
     * Memperbarui data progress yang sudah ada berdasarkan ID.
     *
     * Catatan:
     * - Menggunakan findOrFail untuk memastikan progress tersedia
     * - Hanya memperbarui field yang dikirim pada parameter $data
     * - Tidak menangani dokumen atau relasi lain
     */
    public function updateProgress(string $progressId, array $data)
    {
        $progress = ProjectProgress::findOrFail($progressId);

        $progress->update($data);

        return $progress;
    }

    /**
     * Mengambil data progress beserta seluruh dokumen yang terkait.
     *
     * Catatan:
     * - Digunakan ketika membutuhkan akses ke dokumen terkait progress
     *   (misalnya untuk proses update atau validasi)
     * - Menggunakan eager loading relasi 'documents'
     */
    public function getProgressWithDocuments(string $progressId)
    {
        return ProjectProgress::with('documents')
            ->findOrFail($progressId);
    }

    /**
     * Mengambil satu dokumen berdasarkan ID.
     *
     * Catatan:
     * - Digunakan untuk kebutuhan penghapusan atau manipulasi dokumen
     * - Menggunakan findOrFail untuk memastikan dokumen tersedia
     */
    public function getDocument(string $documentId)
    {
        return ProjectDocument::findOrFail($documentId);
    }
}
