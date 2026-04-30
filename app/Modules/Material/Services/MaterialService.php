<?php

namespace App\Modules\Material\Services;

use DomainException;
use App\Models\MasterMaterial;
use App\Modules\Material\Repositories\MaterialRepository;
use App\Contracts\CodeGeneratorInterface;
use Illuminate\Support\Facades\DB;

class MaterialService
{
    /**
     * Inisialisasi service dengan dependency repository dan code generator.
     */
    public function __construct(
        protected MaterialRepository $materialRepository,
        protected CodeGeneratorInterface $codeGenerator
    ) {}

    /**
     * Mengambil data material.
     *
     * Parameter:
     * - search: filter pencarian
     * - paginate: menentukan apakah hasil dipaginasi
     * - perPage: jumlah data per halaman
     *
     * Catatan:
     * - Digunakan untuk kebutuhan tabel (pagination) dan dropdown (non-pagination)
     */
    public function getMaterials(
        ?string $search = null,
        bool $paginate = true,
        int $perPage = 10
    ) {
        if ($paginate) {
            return $this->materialRepository->getPaginated($search, $perPage);
        }

        return $this->materialRepository->getAll($search);
    }

    /**
     * Membuat data material baru.
     *
     * Catatan:
     * - Kombinasi nama + satuan harus unik
     * - Code material di-generate otomatis dengan prefix 'MAT'
     * - Menggunakan transaction untuk menjaga konsistensi data
     */
    public function createMaterial(array $data)
    {
        if ($this->materialRepository->existsByNameAndUnit($data['name'], $data['unit'])) {
            throw new DomainException('Nama material dengan satuan tersebut sudah ada.');
        }

        return DB::transaction(function () use ($data) {
            $data["code"] = $this->codeGenerator->generate(
                MasterMaterial::class,
                'code',
                'MAT'
            );

            return $this->materialRepository->create($data);
        });
    }

    /**
     * Memperbarui data material.
     *
     * Catatan:
     * - Kombinasi nama + satuan harus unik
     */
    public function updateMaterial($id, array $data)
    {
        if ($this->materialRepository->existsByNameAndUnitExcept($id, $data['name'], $data['unit'])) {
            throw new DomainException('Nama material dengan satuan tersebut sudah ada.');
        }

        $material = $this->materialRepository->find($id);

        return $this->materialRepository->update($material, $data);
    }

    /**
     * Menghapus data material.
     */
    public function deleteMaterial($id)
    {
        $material = $this->materialRepository->find($id);

        return $this->materialRepository->delete($material);
    }
}
