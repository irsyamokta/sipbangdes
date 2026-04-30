<?php

namespace App\Modules\Ahsp\Services;

use DomainException;
use App\Modules\Ahsp\Repositories\AhspToolRepository;
use App\Modules\Ahsp\Repositories\AhspRepository;

class AhspToolService
{
    /**
     * Inisialisasi service dengan dependency repository.
     */
    public function __construct(
        protected AhspToolRepository $ahspToolRepository,
        protected AhspRepository $ahspRepository
    ) {}

    /**
     * Mengambil seluruh data alat pada AHSP tertentu.
     *
     * Catatan:
     * - Delegasi langsung ke repository
     * - Digunakan untuk kebutuhan tampilan detail AHSP
     */
    public function getAhspTools($ahspId)
    {
        return $this->ahspToolRepository->getAhspTools($ahspId);
    }

    /**
     * Menambahkan alat ke dalam AHSP.
     *
     * Aturan bisnis:
     * - AHSP harus sudah ada
     */
    public function createAhspTool(array $data)
    {
        if (!$this->ahspRepository->exists($data['ahsp_id'])) {
            throw new DomainException('AHSP tidak ditemukan.');
        }

        if ($this->ahspToolRepository->existsInAhsp($data['ahsp_id'], $data['tool_id'])) {
            throw new DomainException('Nama alat dengan satuan tersebut sudah ada.');
        }

        return $this->ahspToolRepository->create($data);
    }

    /**
     * Memperbarui data alat pada AHSP.
     *
     * Catatan:
     * - Validasi eksistensi dilakukan melalui find
     * - Belum ada aturan bisnis tambahan
     */
    public function updateAhspTool($id, array $data)
    {
        if ($this->ahspToolRepository->existsInAhspExcept($id, $data['ahsp_id'], $data['tool_id'])) {
            throw new DomainException('Nama alat dengan satuan tersebut sudah ada.');
        }

        $ahspTool = $this->ahspToolRepository->find($id);

        return $this->ahspToolRepository->update($ahspTool, $data);
    }

    /**
     * Menghapus alat dari AHSP.
     *
     * Catatan:
     * - Data diambil terlebih dahulu untuk memastikan keberadaan
     * - Tidak ada constraint bisnis tambahan
     */
    public function deleteAhspTool($id)
    {
        $ahspTool = $this->ahspToolRepository->find($id);

        return $this->ahspToolRepository->delete($ahspTool);
    }
}
