<?php

namespace App\Modules\Ahsp\Repositories;

use App\Models\Ahsp;

class AhspRepository
{   
    /**
     * Mengambil seluruh data tanpa pagination.
     *
     * Catatan:
     * - Digunakan untuk kebutuhan dropdown
     * - Mendukung filter pencarian opsional
     */
    public function getAll(?string $search = null)
    {
        return $this->baseQuery($search)->get();
    }

    /**
     * Mengambil data dengan pagination.
     *
     * Catatan:
     * - Mendukung jumlah data dinamis (10, 25, 50, atau semua)
     * - Jika perPage = 'all', maka seluruh data ditampilkan
     * - Query string dipertahankan untuk kebutuhan filter frontend
     */
    public function getPaginated(
        ?string $search = null,
        int|string $perPage = 10
    ) {
        $query = $this->baseQuery($search);

        if ($perPage === 'all') {
            $perPage = $query->count();
        }

        return $this->baseQuery($search)
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Base query untuk AHSP.
     *
     * Catatan:
     * - Digunakan ulang untuk konsistensi query
     * - Menggunakan eager loading untuk menghindari N+1 query
     * - Mendukung filter pencarian berdasarkan kode dan nama pekerjaan
     */
    private function baseQuery(?string $search)
    {
        return Ahsp::query()
            ->with([
                'ahspComponentMaterials.masterMaterial',
                'ahspComponentWages.masterWage',
                'ahspComponentTools.masterTool',
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('work_code', 'like', "%{$search}%")
                        ->orWhere('work_name', 'like', "%{$search}%");
                });
            });
    }
    
    /**
     * Mengambil data AHSP berdasarkan kategori pekerja.
     *
     * Catatan:
     * - Filter opsional
     */
    public function getByCategory(?string $categoryId = null)
    {
        return Ahsp::query()
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('worker_category_id', $categoryId);
            })
            ->get();
    }

    /**
     * Mengambil satu data AHSP berdasarkan ID.
     *
     * Catatan:
     * - Menggunakan findOrFail untuk menjamin data tersedia
     */
    public function find($id)
    {
        return Ahsp::findOrFail($id);
    }

    /**
     * Mengecek duplikasi nama pekerjaan (exclude ID tertentu).
     *
     * Digunakan untuk proses update.
     */
    public function existsByNameExcept($id, string $name)
    {
        return Ahsp::query()->where('id', '!=', $id)
            ->where('work_name', $name)
            ->exists();
    }

    /**
     * Mengecek apakah nama pekerjaan sudah ada.
     *
     * Digunakan untuk proses create.
     */
    public function existsByName(string $name)
    {
        return Ahsp::query()->where('work_name', $name)->exists();
    }

    /**
     * Mengecek apakah AHSP ada berdasarkan ID.
     */
    public function exists($id)
    {
        return Ahsp::query()->where('id', $id)->exists();
    }

    /**
     * Menyimpan data AHSP baru.
     *
     * Catatan:
     * - Validasi dilakukan di layer service
     */
    public function create(array $data)
    {
        return Ahsp::create($data);
    }

    /**
     * Memperbarui data AHSP.
     */
    public function update(Ahsp $ahsp, array $data)
    {
        return $ahsp->update($data);
    }

    /**
     * Menghapus data AHSP.
     */
    public function delete(Ahsp $ahsp)
    {
        return $ahsp->delete();
    }
}
