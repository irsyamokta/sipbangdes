<?php

namespace App\Modules\Rab\Repositories;

use App\Models\OperationalCost;

class OperationalCostRepository
{
    /**
     * Mengambil seluruh data biaya operasional berdasarkan project.
     */
    public function getByProject(string $projectId)
    {
        return OperationalCost::where('project_id', $projectId)->get();
    }

    /**
     * Mengambil satu data biaya operasional berdasarkan ID.
     */
    public function find(string $id)
    {
        return OperationalCost::findOrFail($id);
    }

    /**
     * Menyimpan data biaya operasional baru.
     */
    public function create(array $data)
    {
        return OperationalCost::create($data);
    }

    /**
     * Memperbarui data biaya operasional.
     *
     * Catatan:
     * - Mengembalikan boolean hasil update
     */
    public function update(OperationalCost $operationalCost, array $data)
    {
        return $operationalCost->update($data);
    }

    /**
     * Menghapus data biaya operasional.
     */
    public function delete(OperationalCost $operationalCost)
    {
        return $operationalCost->delete();
    }
}
