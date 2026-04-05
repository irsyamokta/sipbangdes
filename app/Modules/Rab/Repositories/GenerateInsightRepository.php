<?php

namespace App\Modules\Rab\Repositories;

use App\Models\RabAiInsight;

class GenerateInsightRepository
{
    /**
     * Menonaktifkan semua insight aktif berdasarkan project.
     */
    public function deactivateByProject(string $projectId)
    {
        RabAiInsight::where('project_id', $projectId)
            ->update(['is_active' => false]);
    }

    /**
     * Menyimpan insight baru.
     */
    public function create(array $data)
    {
        return RabAiInsight::create($data);
    }

    /**
     * Mengambil insight aktif terbaru berdasarkan project.
     */
    public function getActive(string $projectId)
    {
        return RabAiInsight::where('project_id', $projectId)
            ->where('is_active', true)
            ->latest()
            ->first();
    }

    /**
     * Mengambil seluruh histori insight berdasarkan project.
     */
    public function getHistory(string $projectId)
    {
        return RabAiInsight::where('project_id', $projectId)
            ->latest()
            ->get();
    }

    /**
     * Menghitung jumlah insight dalam satu project.
     */
    public function countByProject(string $projectId)
    {
        return RabAiInsight::where('project_id', $projectId)->count();
    }
}
