<?php

namespace App\Modules\Ahsp\Controllers;

use Throwable;
use DomainException;
use App\Http\Controllers\Controller;
use App\Modules\Ahsp\Services\AhspMaterialService;
use App\Modules\Material\Services\MaterialService;
use App\Modules\Ahsp\Requests\AhspMaterialStoreRequest;
use App\Modules\Ahsp\Requests\AhspMaterialUpdateRequest;

class AhspMaterialController extends Controller
{
    /**
     * Inisialisasi controller dengan dependency service.
     */
    public function __construct(
        protected AhspMaterialService $ahspMaterialService,
        protected MaterialService $materialService
    ) {}

    /**
     * Menyimpan material baru ke AHSP.
     *
     * Catatan:
     * - Validasi dilakukan di FormRequest
     * - DomainException untuk error bisnis
     * - Throwable untuk error sistem
     */
    public function store(AhspMaterialStoreRequest $request)
    {
        try {
            $this->ahspMaterialService->createAhspMaterial($request->validated());

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                'material_id' => $e->getMessage()
            ]);
        } catch (Throwable $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi'
            ]);
        }
    }

    /**
     * Memperbarui data material AHSP.
     *
     * Catatan:
     * - Error bisnis dan error sistem dipisahkan
     */
    public function update(AhspMaterialUpdateRequest $request, $id)
    {
        try {
            $this->ahspMaterialService->updateAhspMaterial($id, $request->validated());

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                'ahsp_id' => $e->getMessage()
            ]);
        } catch (Throwable $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi'
            ]);
        }
    }

    /**
     * Menghapus material dari AHSP.
     *
     * Catatan:
     * - Tidak ada response khusus selain redirect back
     */
    public function destroy($id)
    {
        try {
            $this->ahspMaterialService->deleteAhspMaterial($id);

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
