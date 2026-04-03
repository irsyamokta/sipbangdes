<?php

namespace App\Modules\Ahsp\Services;

use DomainException;
use App\Models\Ahsp;
use App\Modules\Ahsp\Repositories\AhspWageRepository;

class AhspWageService
{
    /**
     * Inisialisasi service dengan dependency repository.
     */
    public function __construct(
        protected AhspWageRepository $ahspWageRepository
    ) {}

    /**
     * Mengambil seluruh data upah pada AHSP tertentu.
     *
     * Catatan:
     * - Delegasi langsung ke repository
     * - Digunakan untuk kebutuhan tampilan detail AHSP
     */
    public function getAhspWages($ahspId)
    {
        return $this->ahspWageRepository->getAhspWages($ahspId);
    }

    /**
     * Menambahkan upah ke dalam AHSP.
     *
     * Aturan bisnis:
     * - AHSP harus sudah ada
     */
    public function createAhspWage(array $data)
    {
        if (!Ahsp::where('id', $data['ahsp_id'])->exists()) {
            throw new DomainException('AHSP tidak ditemukan');
        }

        return $this->ahspWageRepository->create($data);
    }

    /**
     * Memperbarui data upah pada AHSP.
     *
     * Catatan:
     * - Validasi eksistensi dilakukan melalui find
     * - Belum ada aturan bisnis tambahan
     */
    public function updateAhspWage($id, array $data)
    {
        $ahspWage = $this->ahspWageRepository->find($id);

        return $this->ahspWageRepository->update($ahspWage, $data);
    }

    /**
     * Menghapus upah dari AHSP.
     *
     * Catatan:
     * - Data diambil terlebih dahulu untuk memastikan keberadaan
     * - Tidak ada constraint bisnis tambahan
     */
    public function deleteAhspWage($id)
    {
        $ahspWage = $this->ahspWageRepository->find($id);

        return $this->ahspWageRepository->delete($ahspWage);
    }
}
