<?php

namespace App\Modules\TakeOffSheet\Services;

use DomainException;
use Illuminate\Support\Facades\DB;
use App\Modules\TakeOffSheet\Repositories\TakeOffSheetRepository;

class TakeOffSheetService
{
    /**
     * Inisialisasi service dengan dependency repository.
     */
    public function __construct(
        protected TakeOffSheetRepository $takeOffSheetRepository
    ) {}

    /**
     * Mengambil data TOS dengan filter opsional.
     */
    public function getTakeOffSheets(?string $search = null, ?string $projectId = null)
    {
        return $this->takeOffSheetRepository->getPaginated($search, $projectId);
    }

    /**
     * Membuat data TOS baru.
     *
     * Aturan bisnis:
     * - Tidak boleh menambah TOS jika project sudah approved
     * - Nama pekerjaan harus unik
     */
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {

            $status = $this->takeOffSheetRepository->getProjectStatus($data['project_id']);

            $this->ensureApproved($status);

            if ($this->takeOffSheetRepository->existsByNameExcept(
                null,
                $data['work_name'],
                $data['project_id'],
                $data['worker_category_id']
            )) {
                throw new DomainException('Nama pekerjaan sudah digunakan pada proyek & kategori ini.');
            }

            return $this->takeOffSheetRepository->create($data);
        });
    }

    /**
     * Memperbarui data TOS.
     *
     * Aturan bisnis:
     * - Tidak boleh mengubah TOS jika project sudah approved
     * - Validasi duplikasi tetap berlaku
     */
    public function update(string $id, array $data)
    {
        $tos = $this->takeOffSheetRepository->find($id);

        $status = $this->takeOffSheetRepository->getProjectStatus($data['project_id']);

        $this->ensureApproved($status);

        if ($this->takeOffSheetRepository->existsByNameExcept(
            $id,
            $data['work_name'],
            $data['project_id'],
            $data['worker_category_id']
        )) {
            throw new DomainException('Nama pekerjaan sudah digunakan pada proyek & kategori ini.');
        }

        return DB::transaction(function () use ($tos, $data) {
            return $this->takeOffSheetRepository->update($tos, $data);
        });
    }

    /**
     * Menghapus data TOS.
     *
     * Catatan:
     * - Tidak boleh menghapus TOS jika project sudah approved
     */
    public function delete(string $id)
    {
        $tos = $this->takeOffSheetRepository->find($id);

        $status = $this->takeOffSheetRepository->getProjectStatus($tos->project_id);

        $this->ensureApproved($status);

        return $this->takeOffSheetRepository->delete($tos);
    }

    private function ensureApproved($status)
    {
        if ($status === 'approved') {
            throw new DomainException('Proyek sudah disetujui, tidak dapat mengubah data!');
        }
    }
}
