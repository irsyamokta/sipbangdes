<?php

namespace App\Modules\Ahsp\Services;

use DomainException;
use App\Models\Ahsp;
use App\Modules\Ahsp\Repositories\AhspRepository;
use App\Services\CodeGeneratorService;
use Illuminate\Support\Facades\DB;

class AhspService
{
    /**
     * Inisialisasi service dengan dependency repository dan helper service.
     */
    public function __construct(
        private AhspRepository $ahspRepository,
        private CodeGeneratorService $codeGeneratorService
    ) {}

    /**
     * Mengambil seluruh data AHSP dengan filter pencarian opsional.
     *
     * Catatan:
     * - Digunakan untuk halaman index
     * - Search mencakup work_code dan work_name
     */
    public function getAhsp(?string $search)
    {
        return $this->ahspRepository->getAll($search);
    }

    /**
     * Mengambil AHSP berdasarkan kategori pekerja.
     *
     * Catatan:
     * - Filter bersifat opsional
     * - Digunakan untuk kebutuhan dependent dropdown / filtering
     */
    public function getByCategory(?string $categoryId)
    {
        return $this->ahspRepository->getByCategory($categoryId);
    }

    /**
     * Membuat data AHSP baru.
     *
     * Aturan bisnis:
     * - Nama pekerjaan harus unik
     *
     * Catatan:
     * - Menggunakan transaction untuk menjaga konsistensi data
     * - work_code digenerate otomatis
     */
    public function createAhsp(array $data)
    {
        if ($this->ahspRepository->existsByName($data['work_name']))
            throw new DomainException('Nama pekerjaan sudah ada');

        return DB::transaction(function () use ($data) {
            $data['work_code'] = $this->codeGeneratorService->generateDotCode(
                Ahsp::class,
                'work_code',
                'A'
            );

            return $this->ahspRepository->create($data);
        });
    }

    /**
     * Memperbarui data AHSP.
     *
     * Aturan bisnis:
     * - Nama pekerjaan harus tetap unik (kecuali data itu sendiri)
     */
    public function updateAhsp($id, array $data)
    {
        $ahsp = $this->ahspRepository->find($id);

        if ($this->ahspRepository->existsByNameExcept($id, $data['work_name']))
            throw new DomainException('Nama pekerjaan sudah ada');

        $ahsp->update($data);
    }

    /**
     * Menghapus data AHSP.
     *
     * Catatan:
     * - Tidak ada validasi tambahan
     * - Perlu dipertimbangkan constraint bisnis di masa depan
     */
    public function deleteAhsp($id)
    {
        $ahsp = $this->ahspRepository->find($id);

        $ahsp->delete();
    }
}
