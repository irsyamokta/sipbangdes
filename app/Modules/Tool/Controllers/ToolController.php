<?php

namespace App\Modules\Tool\Controllers;

use Throwable;
use DomainException;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Tool\Services\ToolService;
use App\Modules\Unit\Services\UnitService;
use App\Modules\Tool\Requests\ToolStoreRequest;
use App\Modules\Tool\Requests\ToolUpdateRequest;

class ToolController extends Controller
{
    /**
     * Inisialisasi controller dengan dependency service.
     */
    public function __construct(
        protected ToolService $toolService,
        protected UnitService $unitService
    ) {}

    /**
     * Menampilkan halaman daftar alat.
     *
     * Catatan:
     * - Data Tool menggunakan pagination
     * - Unit digunakan untuk dropdown (non-pagination)
     */
    public function index(Request $request)
    {
        $tools = $this->toolService->getTools(
            $request->search,
            true,
            10
        );

        $units = $this->unitService->getUnits(
            null,
            false
        );

        return Inertia::render('Modules/Tools/Index', [
            'tools' => $tools,
            'unitOptions' => $units->map(fn($unit) => [
                'value' => $unit->name,
                'label' => $unit->name,
            ]),
            'filters' => $request->only(['search', 'page'])
        ]);
    }

    /**
     * Menyimpan data alat baru.
     *
     * Catatan:
     * - Validasi dilakukan di FormRequest
     * - DomainException untuk error bisnis
     */
    public function store(ToolStoreRequest $request)
    {
        try {
            $this->toolService->createTool($request->validated());

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
     * Memperbarui data upah.
     */
    public function update(ToolUpdateRequest $request, $id)
    {
        try {
            $this->toolService->updateTool($id, $request->validated());

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
     * Menghapus data upah.
     */
    public function destroy($id)
    {
        try {
            $this->toolService->deleteTool($id);

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
