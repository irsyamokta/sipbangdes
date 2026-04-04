<?php

namespace App\Modules\Project\Services;

use DomainException;
use App\Modules\Project\Repositories\ProjectRepository;
use Illuminate\Support\Facades\DB;

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
            throw new DomainException("Proyek tersebut sudah ada.");
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
            throw new DomainException("Proyek tersebut sudah ada.");
        }

        return $this->projectRepository->update($project, $data);
    }

    /**
     * Menghapus project.
     */
    public function deleteProject($id)
    {
        $project = $this->projectRepository->find($id);

        return $this->projectRepository->delete($project);
    }
}
