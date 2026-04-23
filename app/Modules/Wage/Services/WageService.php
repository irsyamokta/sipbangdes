<?php

namespace App\Modules\Wage\Services;

use App\Models\MasterWage;
use App\Modules\Wage\Repositories\WageRepository;
use App\Contracts\CodeGeneratorInterface;
use Illuminate\Support\Facades\DB;

class WageService
{
    /**
     * Inisialisasi service dengan dependency repository dan code generator.
     */
    public function __construct(
        protected WageRepository $wageRepository,
        protected CodeGeneratorInterface $codeGenerator
    ) {}

    /**
     * Mengambil data upah (wage).
     *
     * Parameter:
     * - search: filter pencarian
     * - paginate: menentukan apakah hasil dipaginasi
     * - perPage: jumlah data per halaman
     *
     * Catatan:
     * - Digunakan untuk tabel (pagination) dan dropdown (non-pagination)
     */
    public function getWages(
        ?string $search = null,
        bool $paginate = true,
        int $perPage = 10
    ) {
        if ($paginate) {
            return $this->wageRepository->getPaginated($search, $perPage);
        }

        return $this->wageRepository->getAll($search);
    }

    /**
     * Membuat data upah baru.
     *
     * Catatan:
     * - Code di-generate otomatis dengan prefix 'UPH'
     * - Menggunakan transaction untuk menjaga konsistensi data
     */
    public function createWage(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data["code"] = $this->codeGenerator->generate(
                MasterWage::class,
                'code',
                'UPH'
            );

            return $this->wageRepository->create($data);
        });
    }

    /**
     * Memperbarui data upah.
     */
    public function updateWage($id, array $data)
    {
        $wage = $this->wageRepository->find($id);

        return $this->wageRepository->update($wage, $data);
    }

    /**
     * Menghapus data upah.
     */
    public function deleteWage($id)
    {
        $wage = $this->wageRepository->find($id);

        return $this->wageRepository->delete($wage);
    }
}
