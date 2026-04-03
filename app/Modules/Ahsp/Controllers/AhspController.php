<?php

namespace App\Modules\Ahsp\Controllers;

use Throwable;
use DomainException;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Ahsp\Services\AhspService;
use App\Modules\Material\Services\MaterialService;
use App\Modules\Wage\Services\WageService;
use App\Modules\Tool\Services\ToolService;
use App\Modules\Unit\Services\UnitService;
use App\Modules\Ahsp\Requests\AhspStoreRequest;
use App\Modules\Ahsp\Requests\AhspUpdateRequest;

class AhspController extends Controller
{
    /**
     * Inisialisasi controller dengan dependency service.
     */
    public function __construct(
        protected AhspService $ahspService,
        protected UnitService $unitService,
        protected MaterialService $materialService,
        protected WageService $wageService,
        protected ToolService $toolService
    ) {}

    /**
     * Menampilkan halaman daftar AHSP.
     *
     * Catatan:
     * - Mengambil data AHSP beserta relasi
     * - Menyediakan data master untuk dropdown (unit, material, upah, alat)
     * - Data diformat untuk kebutuhan frontend (select options)
     */
    public function index(AhspService $service, Request $request)
    {
        $ahsp = $service->getAhsp(request('search'));
        $units = $this->unitService->getUnits(
            null,
            false
        );

        $materials = $this->materialService->getMaterials(
            null,
            false
        );

        $wages = $this->wageService->getWages(
            null,
            false
        );

        $tools = $this->toolService->getTools(
            null,
            false
        );

        return Inertia::render('Modules/Ahsp/Index', [
            'ahsp' => $ahsp,
            'unitOptions' => $units->map(fn($unit) => [
                'value' => $unit->name,
                'label' => $unit->name,
            ]),
            'materialOptions' => $materials->map(fn($material) => [
                'value' => $material->id,
                'label' => $material->name,
            ]),
            'wageOptions' => $wages->map(fn($wage) => [
                'value' => $wage->id,
                'label' => $wage->position,
            ]),
            'toolOptions' => $tools->map(fn($tool) => [
                'value' => $tool->id,
                'label' => $tool->name,
            ]),
            'filters' => [
                'search' => $request->search ?? ""
            ]
        ]);
    }

    /**
     * Menyimpan data AHSP baru.
     *
     * Catatan:
     * - Validasi dilakukan di FormRequest
     * - DomainException untuk error bisnis
     */
    public function store(AhspStoreRequest $request)
    {
        try {
            $this->ahspService->createAhsp($request->validated());

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
     * Memperbarui data AHSP.
     *
     * Catatan:
     * - Error bisnis dan error sistem dipisahkan
     */
    public function update(AhspUpdateRequest $request, $id)
    {
        try {
            $this->ahspService->updateAhsp($id, $request->validated());

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
     * Menghapus data AHSP.
     */
    public function destroy($id)
    {
        try {
            $this->ahspService->deleteAhsp($id);

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
