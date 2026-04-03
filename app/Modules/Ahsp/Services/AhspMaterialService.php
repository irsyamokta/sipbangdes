<?php

namespace App\Modules\Ahsp\Services;

use DomainException;
use App\Models\Ahsp;
use App\Modules\Ahsp\Repositories\AhspMaterialRepository;

class AhspMaterialService
{
    /**
     * Inisialisasi service dengan dependency repository.
     */
    public function __construct(
        protected AhspMaterialRepository $ahspMaterialRepository
    ) {}

    /**
     * Mengambil seluruh material pada AHSP tertentu.
     *
     * Catatan:
     * - Hanya sebagai delegasi ke repository
     * - Digunakan untuk kebutuhan tampilan/detail AHSP
     */
    public function getAhspMaterials($ahspId)
    {
        return $this->ahspMaterialRepository->getAhspMaterials($ahspId);
    }

    /**
     * Menambahkan material ke dalam AHSP.
     *
     * Aturan bisnis:
     * - AHSP harus sudah ada (validasi eksistensi)
     */
    public function createAhspMaterial(array $data)
    {
        if (!Ahsp::where('id', $data['ahsp_id'])->exists()) {
            throw new DomainException('AHSP tidak ditemukan');
        }

        return $this->ahspMaterialRepository->create($data);
    }

    /**
     * Memperbarui data material AHSP.
     *
     * Catatan:
     * - Validasi eksistensi dilakukan melalui find (fail jika tidak ada)
     */
    public function updateAhspMaterial($id, array $data)
    {
        $ahspMaterial = $this->ahspMaterialRepository->find($id);

        return $this->ahspMaterialRepository->update($ahspMaterial, $data);
    }

    /**
     * Menghapus material dari AHSP.
     *
     * Catatan:
     * - Data akan diambil terlebih dahulu untuk memastikan keberadaan
     * - Tidak ada constraint bisnis tambahan (misal: status lock)
     */
    public function deleteAhspMaterial($id)
    {
        $ahspMaterial = $this->ahspMaterialRepository->find($id);

        return $this->ahspMaterialRepository->delete($ahspMaterial);
    }
}
