<?php

namespace App\Modules\Tool\Services;

use DomainException;
use App\Models\MasterTool;
use App\Modules\Tool\Repositories\ToolRepository;
use App\Services\CodeGeneratorService;
use Illuminate\Support\Facades\DB;

class ToolService
{
    /**
     * Inisialisasi service dengan dependency repository dan code generator.
     */
    public function __construct(
        protected ToolRepository $toolRepository,
        protected CodeGeneratorService $codeGenerator
    ) {}

    /**
     * Mengambil data alat (tool).
     *
     * Parameter:
     * - search: filter pencarian
     * - paginate: menentukan apakah hasil dipaginasi
     * - perPage: jumlah data per halaman
     *
     * Catatan:
     * - Digunakan untuk tabel (pagination) dan dropdown (non-pagination)
     */
    public function getTools(
        ?string $search = null,
        bool $paginate = true,
        int $perPage = 10
    ) {
        if ($paginate) {
            return $this->toolRepository->getPaginated($search, $perPage);
        }

        return $this->toolRepository->getAll($search);
    }

    /**
     * Membuat data alat baru.
     *
     * Catatan:
     * - Code di-generate otomatis dengan prefix 'TL'
     * - Menggunakan transaction untuk menjaga konsistensi data
     */
    public function createTool(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data["code"] = $this->codeGenerator->generate(
                MasterTool::class,
                'code',
                'TL'
            );

            return $this->toolRepository->create($data);
        });
    }

    /**
     * Memperbarui data alat.
     */
    public function updateTool($id, array $data)
    {
        $tool = $this->toolRepository->find($id);

        return $this->toolRepository->update($tool, $data);
    }

    /**
     * Menghapus data alat.
     */
    public function deleteTool($id)
    {
        $tool = $this->toolRepository->find($id);

        return $this->toolRepository->delete($tool);
    }
}
