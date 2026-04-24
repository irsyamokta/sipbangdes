<?php

namespace App\Modules\Ahsp\Services;

use App\Models\Ahsp;
use App\Modules\Ahsp\Repositories\AhspRepository;
use App\Services\CodeGenerators\DotCodeGenerator;
use DomainException;
use Illuminate\Support\Facades\DB;

class AhspService
{
    /**
     * Inisialisasi service dengan dependency repository dan helper service.
     */
    public function __construct(
        private AhspRepository $ahspRepository,
        private DotCodeGenerator $codeGenerator
    ) {}

    /**
     * Mengambil data AHSP dengan dukungan pencarian dan pagination.
     *
     * Catatan:
     * - Digunakan untuk halaman index
     * - Mendukung filter pencarian berdasarkan work_code dan work_name
     * - Mendukung jumlah data dinamis (10, 25, 50, atau semua)
     * - Jika perPage = 'all', maka seluruh data akan ditampilkan
     */
    public function getAhsp(
        ?string $search,
        int|string $perPage = 10
    ) {
        return $this->ahspRepository->getPaginated($search, $perPage);
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
        if ($this->ahspRepository->existsByName($data['work_name'])) {
            throw new DomainException('Nama pekerjaan sudah ada');
        }

        return DB::transaction(function () use ($data) {
            $data['work_code'] = $this->codeGenerator->generate(
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

        if ($this->ahspRepository->existsByNameExcept($id, $data['work_name'])) {
            throw new DomainException('Nama pekerjaan sudah ada');
        }

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
