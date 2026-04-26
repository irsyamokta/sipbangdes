<?php

namespace App\Modules\TakeOffSheet\Controllers;

use Throwable;
use DomainException;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WorkerItem;
use App\Modules\Project\Services\ProjectService;
use App\Modules\WorkerCategory\Services\WorkerCategoryService;
use App\Modules\Ahsp\Services\AhspService;
use App\Modules\Unit\Services\UnitService;
use App\Modules\TakeOffSheet\Services\TakeOffSheetService;
use App\Modules\TakeOffSheet\Requests\TakeOffSheetStoreRequest;
use App\Modules\TakeOffSheet\Requests\TakeOffSheetUpdateRequest;

class TakeOffSheetController extends Controller
{
    /**
     * Inisialisasi controller dengan dependency service.
     */
    public function __construct(
        protected TakeOffSheetService $takeOffSheetService,
        protected ProjectService $projectService,
        protected WorkerCategoryService $workerCategoryService,
        protected AhspService $ahspService,
        protected UnitService $unitService
    ) {}

    /**
     * Menampilkan halaman daftar TOS.
     *
     * Catatan:
     * - AHSP diambil melalui WorkerItem untuk mendapatkan relasi kategori
     * - Data disiapkan dalam format select option untuk frontend
     */
    public function index(Request $request)
    {
        $data = $this->takeOffSheetService->getTakeOffSheets(
            $request->search,
            $request->project_id
        );

        $project = $this->projectService->getProjects();
        $workerCategory = $this->workerCategoryService->getWorkerCategory(null, 'all');
        $units = $this->unitService->getUnits(null, false);

        return Inertia::render('Modules/TakeOffSheet/Index', [
            'takeOffSheets' => $data,

            'projectOptions' => $project->map(fn($project) => [
                'value' => $project->id,
                'label' => $project->project_name . " (". $project->budget_year . ")"
            ]),

            'workerCategoryOptions' => $workerCategory->map(fn($workerCategory) => [
                'value' => $workerCategory->id,
                'label' => $workerCategory->name
            ]),

            'ahspOptions' => WorkerItem::with('ahsp')
                ->get()
                ->map(fn($item) => [
                    'value' => $item->ahsp_id,
                    'label' => $item->ahsp->work_code . ' - ' . $item->ahsp->work_name,
                    'category_id' => $item->category_id,
                    'data' => [
                        'work_name' => $item->ahsp->work_name,
                        'unit' => $item->ahsp->unit
                    ]
                ]),

            'unitOptions' => $units->map(fn($unit) => [
                'value' => $unit->name,
                'label' => $unit->name
            ]),

            'filters' => $request->only([
                'search',
                'project_id',
            ]),
        ]);
    }

    /**
     * Menyimpan data TOS baru.
     *
     * Catatan:
     * - Validasi dilakukan di FormRequest
     * - DomainException digunakan untuk error bisnis (misal duplikasi)
     */
    public function store(TakeOffSheetStoreRequest $request)
    {
        try {
            $this->takeOffSheetService->create($request->validated());

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                'work_name' => $e->getMessage()
            ]);
        } catch (Throwable $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi'
            ]);
        }
    }

    /**
     * Memperbarui data TOS.
     *
     * Catatan:
     * - Error bisnis dan error sistem dipisahkan untuk kontrol pesan
     */
    public function update(TakeOffSheetUpdateRequest $request, $id)
    {
        try {
            $this->takeOffSheetService->update($id, $request->validated());

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                'work_name' => $e->getMessage()
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
            $this->takeOffSheetService->delete($id);

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
