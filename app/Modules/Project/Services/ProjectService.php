<?php

namespace App\Modules\Project\Services;

use App\Modules\Project\Repositories\ProjectRepository;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use DomainException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProjectService
{
    /**
     * Inisialisasi service dengan dependency repository.
     */
    public function __construct(
        protected ProjectRepository $projectRepository,
    ) {}

    /**
     * Mengambil daftar project dengan filter opsional.
     *
     * Catatan:
     * - Filter bersifat opsional dan dikombinasikan secara dinamis
     * - Pencarian nama menggunakan partial match
     */
    public function getProjects(?string $search = null, ?string $year = null)
    {
        return $this->projectRepository->getAll($search, $year);
    }

    /**
     * Membuat project baru.
     *
     * Aturan bisnis:
     * - Kombinasi (project_name, budget_year, location) harus unik
     * - Digunakan untuk mencegah duplikasi project dalam konteks yang sama
     *
     * Catatan:
     * - Diasumsikan data sudah tervalidasi di layer Request
     * - Menggunakan transaction untuk menjaga konsistensi data
     *
     * @throws DomainException jika project duplikat ditemukan
     */
    public function createProject(array $data)
    {
        if ($this->projectRepository->isDuplicate(
            $data['project_name'],
            $data['budget_year'],
            $data['location']
        )) {
            throw new DomainException('Proyek tersebut sudah ada.');
        }

        return DB::transaction(function () use ($data) {
            return $this->projectRepository->create($data);
        });
    }

    /**
     * Memperbarui data project.
     *
     * Aturan bisnis:
     * - Kombinasi (project_name, budget_year, location) tetap harus unik
     * - Data project saat ini tidak dihitung sebagai duplikat (ignore self)
     *
     * Catatan:
     * - Tidak melakukan pengecekan null, diasumsikan sudah ditangani di layer atas
     *
     * @throws DomainException jika project duplikat ditemukan
     */
    public function updateProject($id, array $data)
    {
        $project = $this->projectRepository->find($id);

        if ($this->projectRepository->isDuplicate(
            $data['project_name'],
            $data['budget_year'],
            $data['location'],
            $project->id
        )) {
            throw new DomainException('Proyek tersebut sudah ada.');
        }

        return $this->projectRepository->update($project, $data);
    }

    /**
     * Menghapus project beserta dokumen terkait.
     *
     * Proses:
     * - Mengambil data project berdasarkan ID
     * - Menghapus file dokumen dari Cloudinary (jika memiliki public_id)
     * - Menghapus data project dari database
     *
     * Catatan:
     * - Penghapusan file eksternal tidak menghentikan proses jika gagal (error hanya di-log)
     * - Menggunakan transaction untuk menjaga konsistensi data
     */
    public function deleteProject($id)
    {
        return DB::transaction(function () use ($id) {
            $project = $this->projectRepository->find($id);

            $documents = $project->projectDocuments;

            if ($documents) {
                foreach ($documents as $document) {
                    if ($document->public_id) {
                        try {
                            Cloudinary::uploadApi()->destroy($document->public_id);
                        } catch (Throwable $e) {
                            Log::error($e->getMessage());
                        }
                    }
                }
            }

            return $this->projectRepository->delete($project);
        });
    }
}
