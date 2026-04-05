<?php

namespace App\Modules\Rab\Controllers;

use Throwable;
use DomainException;
use App\Http\Controllers\Controller;
use App\Modules\Rab\Requests\OperationalCostRequest;
use App\Modules\Rab\Services\OperationalCostService;

class OperationalCostController extends Controller
{
    /**
     * Inisialisasi controller dengan dependency service.
     */
    public function __construct(
        protected OperationalCostService $operationalCostService
    ) {}

    /**
     * Menyimpan data biaya operasional baru.
     *
     * Catatan:
     * - Validasi dilakukan melalui FormRequest
     * - Error bisnis ditangani menggunakan DomainException
     */
    public function store(OperationalCostRequest $request)
    {
        try {
            $this->operationalCostService->store($request->validated());
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

    /**
     * Memperbarui data biaya operasional.
     *
     * Catatan:
     * - Validasi dilakukan melalui FormRequest
     * - Error bisnis ditangani menggunakan DomainException
     */
    public function update(OperationalCostRequest $request, $id)
    {
        try {
            $this->operationalCostService->update($id, $request->validated());
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

    /**
     * Menghapus data biaya operasional.
     */
    public function destroy($id)
    {
        try {
            $this->operationalCostService->destroy($id);
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
