<?php

namespace App\Modules\Project\Repositories;

use App\Models\Project;

class ProjectRepository
{
    /**
     * Mengambil daftar project dengan filter opsional.
     *
     * Catatan:
     * - Relasi 'latestProgress' di-load untuk kebutuhan tampilan ringkas
     * - Filter bersifat dinamis (hanya diterapkan jika parameter tersedia)
     */
    public function getAll(?string $search = null, ?string $year = null)
    {
        return Project::query()
            ->with('latestProgress')
            ->when($search, function ($query) use ($search) {
                $query->where('project_name', 'like', "%{$search}%");
            })
            ->when($year && $year !== 'all', function ($query) use ($year) {
                $query->where('budget_year', $year);
            })
            ->latest()
            ->get();
    }

    /**
     * Mengambil satu project berdasarkan ID.
     *
     * Catatan:
     * - Mengembalikan null jika data tidak ditemukan
     */
    public function find($id)
    {
        return Project::find($id);
    }

    /**
     * Menyimpan data project baru ke database.
     *
     * Catatan:
     * - Diasumsikan data sudah tervalidasi di layer atas
     */
    public function create(array $data)
    {
        return Project::create($data);
    }

    /**
     * Memperbarui data project yang ada.
     *
     * Catatan:
     * - Menggunakan instance model untuk menjaga konsistensi state
     */
    public function update(Project $project, array $data)
    {
        $project->update($data);
        return $project;
    }

    /**
     * Menghapus project dari database.
     */
    public function delete(Project $project)
    {
        return $project->delete();
    }

    /**
     * Mengecek apakah terdapat project dengan kombinasi unik yang sama.
     *
     * Aturan:
     * - Kombinasi (project_name, budget_year, location) harus unik
     * - Digunakan untuk validasi sebelum create/update
     *
     * @param string      $name
     * @param string      $year
     * @param string      $location
     * @param string|null $ignoreId ID yang dikecualikan (digunakan saat update)
     *
     * @return bool true jika duplikat ditemukan
     */
    public function isDuplicate(string $name, string $year, string $location, ?string $ignoreId = null)
    {
        return Project::query()
            ->where('project_name', $name)
            ->where('budget_year', $year)
            ->where('location', $location)
            ->when($ignoreId, function ($query) use ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            })
            ->exists();
    }
}
