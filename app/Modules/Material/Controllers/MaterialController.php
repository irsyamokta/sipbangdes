<?php

namespace App\Modules\Material\Controllers;

use Throwable;
use DomainException;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Material\Services\MaterialService;
use App\Modules\Unit\Services\UnitService;
use App\Modules\Material\Requests\MaterialStoreRequest;
use App\Modules\Material\Requests\MaterialUpdateRequest;

class MaterialController extends Controller
{
    /**
     * Inisialisasi controller dengan dependency service.
     */
    public function __construct(
        protected MaterialService $materialService,
        protected UnitService $unitService
    ) {}

    /**
     * Menampilkan halaman daftar material.
     *
     * Catatan:
     * - Data material menggunakan pagination
     * - Unit digunakan sebagai dropdown (non-pagination)
     */
    public function index(Request $request)
    {
        $materials = $this->materialService->getMaterials(
            $request->search,
            true,
            10
        );

        $units = $this->unitService->getUnits(
            null,
            false
        );

        return Inertia::render('Modules/Materials/Index', [
            'materials' => $materials,
            'unitOptions' => $units->map(fn($unit) => [
                'value' => $unit->name,
                'label' => $unit->name,
            ]),
            'filters' => $request->only(['search', 'page']),
        ]);
    }

    /**
     * Menyimpan data material baru.
     *
     * Catatan:
     * - Validasi dilakukan di FormRequest
     * - DomainException untuk validasi bisnis
     */
    public function store(MaterialStoreRequest $request)
    {
        try {
            $this->materialService->createMaterial($request->validated());

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
     * Memperbarui data material.
     */
    public function update(MaterialUpdateRequest $request, $id)
    {
        try {
            $this->materialService->updateMaterial($id, $request->validated());

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
     * Menghapus data material.
     */
    public function destroy($id)
    {
        try {
            $this->materialService->deleteMaterial($id);

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
