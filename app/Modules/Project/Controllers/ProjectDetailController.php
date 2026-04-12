<?php

namespace App\Modules\Project\Controllers;

use Throwable;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Modules\Project\Services\ProjectDetailService;

class ProjectDetailController extends Controller
{
    /**
     * Inisialisasi controller dengan dependency service.
     */
    public function __construct(
        protected ProjectDetailService $service
    ) {}

    /**
     * Menampilkan halaman detail project.
     *
     * Catatan:
     * - Mengambil seluruh data detail project dari service
     * - Mengirim data ke halaman frontend
     * - Redirect ke halaman index jika project tidak ditemukan
     */
    public function show(string $id)
    {
        try {
            $data = $this->service->getDetail($id);

            return Inertia::render('Modules/Projects/Detail', [
                ...$data
            ]);
        } catch (Throwable $e) {
            return redirect()
                ->route('project.index')
                ->withErrors([
                    'Proyek tidak ditemukan'
                ]);
        }
    }
}
