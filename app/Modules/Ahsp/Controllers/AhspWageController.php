<?php

namespace App\Modules\Ahsp\Controllers;

use Throwable;
use DomainException;
use App\Http\Controllers\Controller;
use App\Modules\Ahsp\Services\AhspWageService;
use App\Modules\Wage\Services\WageService;
use App\Modules\Ahsp\Requests\AhspWageStoreRequest;
use App\Modules\Ahsp\Requests\AhspWageUpdateRequest;

class AhspWageController extends Controller
{
    /**
     * Inisialisasi controller dengan dependency service.
     */
    public function __construct(
        protected AhspWageService $ahspWageService,
        protected WageService $wageService
    ) {}

    /**
     * Menyimpan data upah ke dalam AHSP.
     *
     * Catatan:
     * - Validasi dilakukan di FormRequest
     * - DomainException untuk error bisnis
     */
    public function store(AhspWageStoreRequest $request)
    {
        try {
            $this->ahspWageService->createAhspWage($request->validated());

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                'wage_id' => $e->getMessage()
            ]);
        } catch (Throwable $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi'
            ]);
        }
    }

    /**
     * Memperbarui data upah pada AHSP.
     *
     * Catatan:
     * - Error bisnis dan error sistem dipisahkan
     */
    public function update(AhspWageUpdateRequest $request, $id)
    {
        try {
            $this->ahspWageService->updateAhspWage($id, $request->validated());

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                'wage_id' => $e->getMessage()
            ]);
        } catch (Throwable $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi'
            ]);
        }
    }

    /**
     * Menghapus data upah dari AHSP.
     */
    public function destroy($id)
    {
        try {
            $this->ahspWageService->deleteAhspWage($id);

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
