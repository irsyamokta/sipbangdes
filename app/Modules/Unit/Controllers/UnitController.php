<?php

namespace App\Modules\Unit\Controllers;

use Throwable;
use DomainException;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Unit\Services\UnitService;
use App\Modules\Unit\Requests\UnitStoreRequest;
use App\Modules\Unit\Requests\UnitUpdateRequest;

class UnitController extends Controller
{
    /**
     * Inisialisasi controller dengan dependency service.
     */
    public function __construct(
        protected UnitService $unitService
    ) {}

    /**
     * Menampilkan halaman daftar satuan.
     *
     * Catatan:
     * - Menggunakan pagination
     * - Filter dikirim ke frontend
     */
    public function index(Request $request)
    {
        $units = $this->unitService->getUnits(
            $request->search,
            true,
            10
        );

        return Inertia::render('Modules/Units/Index', [
            'units' => $units,
            'filters' => $request->only(['search', 'page']),
        ]);
    }

    /**
     * Menyimpan data satuan baru.
     *
     * Catatan:
     * - Validasi dilakukan di FormRequest
     * - DomainException untuk error bisnis (duplikasi)
     */
    public function store(UnitStoreRequest $request)
    {
        try {
            $this->unitService->createUnit($request->validated());

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
     * Memperbarui data satuan.
     */
    public function update(UnitUpdateRequest $request, $id)
    {
        try {
            $this->unitService->updateUnit($id, $request->validated());

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
     * Menghapus data satuan.
     */
    public function destroy($id)
    {
        try {
            $this->unitService->deleteUnit($id);

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
