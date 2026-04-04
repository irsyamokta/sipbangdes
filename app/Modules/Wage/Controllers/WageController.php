<?php

namespace App\Modules\Wage\Controllers;

use Throwable;
use DomainException;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Wage\Services\WageService;
use App\Modules\Unit\Services\UnitService;
use App\Modules\Wage\Requests\WageStoreRequest;
use App\Modules\Wage\Requests\WageUpdateRequest;

class WageController extends Controller
{
    /**
     * Inisialisasi controller dengan dependency service.
     */
    public function __construct(
        protected WageService $wageService,
        protected UnitService $unitService
    ) {}

    /**
     * Menampilkan halaman daftar upah.
     *
     * Catatan:
     * - Data wage menggunakan pagination
     * - Unit digunakan untuk dropdown (non-pagination)
     */
    public function index(Request $request)
    {
        $wages = $this->wageService->getWages(
            $request->search,
            true,
            10
        );

        $units = $this->unitService->getUnits(
            null,
            false
        );

        return Inertia::render('Modules/Wages/Index', [
            'wages' => $wages,
            'unitOptions' => $units->map(fn($unit) => [
                'value' => $unit->name,
                'label' => $unit->name,
            ]),
            'filters' => $request->only(['search', 'page'])
        ]);
    }

    /**
     * Menyimpan data upah baru.
     *
     * Catatan:
     * - Validasi dilakukan di FormRequest
     * - DomainException untuk error bisnis
     */
    public function store(WageStoreRequest $request)
    {
        try {
            $this->wageService->createWage($request->validated());

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                'position' => $e->getMessage()
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
    public function update(WageUpdateRequest $request, $id)
    {
        try {
            $this->wageService->updateWage($id, $request->validated());

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                'position' => $e->getMessage()
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
            $this->wageService->deleteWage($id);

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
