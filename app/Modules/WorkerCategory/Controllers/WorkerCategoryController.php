<?php

namespace App\Modules\WorkerCategory\Controllers;

use Throwable;
use DomainException;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\WorkerCategory\Services\WorkerCategoryService;
use App\Modules\Ahsp\Services\AhspService;
use App\Modules\Unit\Services\UnitService;
use App\Modules\WorkerCategory\Requests\WorkerCategoryStoreRequest;
use App\Modules\WorkerCategory\Requests\WorkerCategoryUpdateRequest;

class WorkerCategoryController extends Controller
{
    /**
     * Inisialisasi controller dengan dependency service.
     */
    public function __construct(
        protected WorkerCategoryService $workerCategoryService,
        protected UnitService $unitService,
        protected AhspService $ahspService
    ) {}

    /**
     * Menampilkan halaman daftar kategori pekerjaan.
     *
     * Catatan:
     * - Mengambil data kategori beserta relasi
     * - Menyediakan data AHSP dan unit untuk kebutuhan dropdown
     * - Data diformat menjadi select option untuk frontend
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $workerCategories = $this->workerCategoryService->getWorkerCategory(
            $request->search,
            $perPage
        );
        $ahsp = $this->ahspService->getAhsp(null, 'all');
        $units = $this->unitService->getUnits(null, false);

        return Inertia::render('Modules/WorkerCategory/Index', [
            'workerCategories' => $workerCategories,

            'unitOptions' => $units->map(fn($unit) => [
                'value' => $unit->name,
                'label' => $unit->name
            ]),

            'ahspOptions' => $ahsp->map(fn($ahsp) => [
                'value' => $ahsp->id,
                'label' => $ahsp->work_code . ' - ' . $ahsp->work_name,
                'data' => [
                    'work_name' => $ahsp->work_name,
                    'unit'=> $ahsp->unit
                ]
            ]),
            
            'filters' => [
                'search' => $request->search ?? '',
                'per_page' => $perPage,
            ],
        ]);
    }

    /**
     * Menyimpan kategori pekerjaan baru.
     *
     * Catatan:
     * - Validasi dilakukan di FormRequest
     * - DomainException untuk error bisnis
     */
    public function store(WorkerCategoryStoreRequest $request)
    {
        try {
            $this->workerCategoryService->createWorkerCategory($request->validated());

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                'name' => $e->getMessage()
            ]);
        } catch (Throwable $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi'
            ]);
        }
    }

    /**
     * Memperbarui kategori pekerjaan.
     *
     * Catatan:
     * - Error bisnis dan error sistem dipisahkan
     */
    public function update(WorkerCategoryUpdateRequest $request, $id)
    {
        try {
            $this->workerCategoryService->updateWorkerCategory($id, $request->validated());

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                'name' => $e->getMessage()
            ]);
        } catch (Throwable $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi'
            ]);
        }
    }

    /**
     * Menghapus kategori pekerjaan.
     */
    public function destroy($id)
    {
        try {
            $this->workerCategoryService->deleteWorkerCategory($id);

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
