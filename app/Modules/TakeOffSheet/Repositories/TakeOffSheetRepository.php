<?php

namespace App\Modules\TakeOffSheet\Repositories;

use App\Models\TakeOffSheet;
use App\Models\Project;

class TakeOffSheetRepository
{
    /**
     * Mengambil seluruh data TOS tanpa pagination.
     *
     * Catatan:
     * - Menggunakan baseQuery untuk konsistensi filter
     */
    public function getAll(?string $search = null, ?string $projectId = null)
    {
        return $this->baseQuery($search, $projectId)->get();
    }

    /**
     * Mengambil data TOS dengan pagination.
     *
     * Catatan:
     * - Query string dipertahankan untuk kebutuhan filter di frontend
     */
    public function getPaginated(
        ?string $search = null,
        ?string $projectId = null,
        int $perPage = 10
    ) {
        return $this->baseQuery($search, $projectId)
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Base query untuk TOS.
     *
     * Catatan:
     * - Relasi utama di-load untuk kebutuhan tampilan
     * - Filter bersifat dinamis (optional)
     */
    private function baseQuery(?string $search, ?string $projectId)
    {
        return TakeOffSheet::query()
            ->with([
                'project',
                'ahsp',
                'workerCategory',
            ])

            ->when($projectId && $projectId !== 'all', function ($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })

            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('work_name', 'like', "%{$search}%");
                });
            })

            ->latest();
    }

    /**
     * Mengambil status project.
     *
     * Digunakan untuk validasi bisnis (misal: block jika approved)
     */
    public function getProjectStatus(?string $projectId)
    {
        return Project::where('id', $projectId)->value('rab_status');
    }

    /**
     * Mengambil satu data TOS berdasarkan ID.
     *
     * Catatan:
     * - Menggunakan findOrFail untuk menjamin data tersedia
     */
    public function find($id)
    {
        return TakeOffSheet::with([
            'project',
            'ahsp',
            'workerCategory',
        ])->findOrFail($id);
    }

    /**
     * Mengecek duplikasi nama pekerjaan dalam proyek & kategori tertentu.
     *
     * Aturan:
     * - Nama pekerjaan harus unik dalam kombinasi tersebut
     * - Digunakan untuk create & update
     */
    public function existsByNameExcept(
        ?string $id,
        string $name,
        string $projectId,
        string $workerCategoryId
    ) {
        return TakeOffSheet::query()
            ->when($id, fn($q) => $q->where('id', '!=', $id))
            ->where('work_name', $name)
            ->where('project_id', $projectId)
            ->where('worker_category_id', $workerCategoryId)
            ->exists();
    }

    /**
     * Menyimpan data TOS baru.
     *
     * Catatan:
     * - Validasi dilakukan di layer service
     */
    public function create(array $data)
    {
        return TakeOffSheet::create($data);
    }

    /**
     * Memperbarui data TOS.
     */
    public function update(TakeOffSheet $tos, array $data)
    {
        $tos->update($data);
        return $tos;
    }

    /**
     * Menghapus data TOS.
     */
    public function delete(TakeOffSheet $tos)
    {
        return $tos->delete();
    }
}
