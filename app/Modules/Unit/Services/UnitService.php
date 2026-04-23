<?php

namespace App\Modules\Unit\Services;

use DomainException;
use App\Models\MasterUnit;
use App\Modules\Unit\Repositories\UnitRepository;
use App\Contracts\CodeGeneratorInterface;
use Illuminate\Support\Facades\DB;

class UnitService
{
    /**
     * Inisialisasi service dengan dependency repository dan code generator.
     */
    public function __construct(
        protected UnitRepository $unitRepository,
        protected CodeGeneratorInterface $codeGenerator
    ) {}

    /**
     * Mengambil data satuan.
     *
     * Parameter:
     * - search: filter pencarian
     * - paginate: menentukan apakah hasil dipaginasi
     * - perPage: jumlah data per halaman
     *
     * Catatan:
     * - Fleksibel untuk kebutuhan dropdown (tanpa pagination) dan tabel (dengan pagination)
     */
    public function getUnits(
        ?string $search = null,
        bool $paginate = true,
        int $perPage = 10
    ) {
        if ($paginate) {
            return $this->unitRepository->getPaginated($search, $perPage);
        }

        return $this->unitRepository->getAll($search);
    }

    /**
     * Membuat data satuan baru.
     *
     * Aturan bisnis:
     * - Nama satuan harus unik
     *
     * Catatan:
     * - Code satuan di-generate otomatis dengan prefix 'SAT'
     * - Menggunakan transaction untuk menjaga konsistensi
     */
    public function createUnit(array $data)
    {
        if ($this->unitRepository->existsByName($data["name"]))
            throw new DomainException("Nama satuan sudah ada.");

        return DB::transaction(function () use ($data) {
            $data["code"] = $this->codeGenerator->generate(
                MasterUnit::class,
                'code',
                'SAT'
            );

            return $this->unitRepository->create($data);
        });
    }

    /**
     * Memperbarui data satuan.
     *
     * Aturan bisnis:
     * - Nama satuan harus tetap unik (kecuali data itu sendiri)
     */
    public function updateUnit($id, array $data)
    {
        $unit = $this->unitRepository->find($id);

        if ($this->unitRepository->existsByNameExcept($id, $data["name"]))
            throw new DomainException("Nama satuan sudah ada.");

        return $this->unitRepository->update($unit, $data);
    }

    /**
     * Menghapus data satuan.
     */
    public function deleteUnit($id)
    {
        $unit = $this->unitRepository->find($id);

        return $this->unitRepository->delete($unit);
    }
}
