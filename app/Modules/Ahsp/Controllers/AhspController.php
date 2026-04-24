<?php

namespace App\Modules\Ahsp\Controllers;

use DomainException;
use Throwable;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Ahsp\Requests\AhspStoreRequest;
use App\Modules\Ahsp\Requests\AhspUpdateRequest;
use App\Modules\Ahsp\Services\AhspService;
use App\Modules\Material\Services\MaterialService;
use App\Modules\Tool\Services\ToolService;
use App\Modules\Unit\Services\UnitService;
use App\Modules\Wage\Services\WageService;

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
     * - Mengambil data AHSP dengan dukungan pencarian dan pagination dinamis
     * - Jumlah data per halaman dapat diatur melalui parameter per_page
     *   (10, 25, 50, atau 'all')
     * - Mengambil data master (unit, material, upah, alat) untuk kebutuhan dropdown
     * - Data master diformat menjadi select options untuk frontend
     * - Parameter filter dikirim kembali ke frontend untuk mempertahankan state filter
     */
    public function index(AhspService $service, Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $ahsp = $service->getAhsp(
            $request->search,
            $perPage
        );

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
            'unitOptions' => $units->map(fn ($unit) => [
                'value' => $unit->name,
                'label' => $unit->name,
            ]),
            'materialOptions' => $materials->map(fn ($material) => [
                'value' => $material->id,
                'label' => $material->name.' ('.$material->unit.')',
            ]),
            'wageOptions' => $wages->map(fn ($wage) => [
                'value' => $wage->id,
                'label' => $wage->position.' ('.$wage->unit.')',
            ]),
            'toolOptions' => $tools->map(fn ($tool) => [
                'value' => $tool->id,
                'label' => $tool->name.' ('.$tool->unit.')',
            ]),
            'filters' => [
                'search' => $request->search ?? '',
                'per_page' => $perPage,
            ],
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
                'work_name' => $e->getMessage(),
            ]);
        } catch (Throwable $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi',
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
                'work_name' => $e->getMessage(),
            ]);
        } catch (Throwable $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi',
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
                $e->getMessage(),
            ]);
        } catch (Throwable $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi',
            ]);
        }
    }
}
