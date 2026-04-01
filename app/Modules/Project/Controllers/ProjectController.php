<?php

namespace App\Modules\Project\Controllers;

use Throwable;
use DomainException;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Project\Services\ProjectService;
use App\Modules\Unit\Services\UnitService;
use App\Modules\Project\Requests\ProjectStoreRequest;
use App\Modules\Project\Requests\ProjectUpdateRequest;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectService $service,
        protected UnitService $unitService
    ) {}

    /**
     * Menampilkan halaman daftar project.
     *
     * Catatan:
     * - Data difilter berdasarkan query param (search, year)
     * - Unit di-transform menjadi format select option untuk frontend
     */
    public function index(Request $request)
    {
        $projects = $this->service->getProjects($request->search, $request->year);
        $units = $this->unitService->getUnits(null, false);

        return Inertia::render('Modules/Projects/Index', [
            'projects' => $projects,
            'unitOptions' => $units->map(fn($unit) => [
                'value' => $unit->name,
                'label' => $unit->name,
            ]),
            'filters' => $request->only(['search', 'year']),
        ]);
    }

    /**
     * Menyimpan project baru.
     *
     * Catatan:
     * - Validasi input dilakukan di FormRequest
     * - DomainException digunakan untuk error bisnis (misal duplikasi)
     */
    public function store(ProjectStoreRequest $request)
    {
        try {
            $this->service->createProject($request->validated());

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                'project_name' => $e->getMessage()
            ]);
        } catch (Throwable $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi'
            ]);
        }
    }

    /**
     * Memperbarui data project.
     *
     * Catatan:
     * - Error bisnis dan error sistem dipisahkan untuk kontrol pesan
     */
    public function update(ProjectUpdateRequest $request, $id)
    {
        try {
            $this->service->updateProject($id, $request->validated());

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                'project_name' => $e->getMessage()
            ]);
        } catch (Throwable $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi'
            ]);
        }
    }

    /**
     * Menghapus project.
     */
    public function destroy($id)
    {
        try {
            $this->service->deleteProject($id);

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                $e->getMessage()
            ]);
        } catch (Throwable $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi'
            ]);
        }
    }
}
