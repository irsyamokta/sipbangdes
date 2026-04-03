<?php

namespace App\Modules\Ahsp\Controllers;

use Throwable;
use DomainException;
use App\Http\Controllers\Controller;
use App\Modules\Ahsp\Services\AhspToolService;
use App\Modules\Tool\Services\ToolService;
use App\Modules\Ahsp\Requests\AhspToolStoreRequest;
use App\Modules\Ahsp\Requests\AhspToolUpdateRequest;

class AhspToolController extends Controller
{
    /**
     * Inisialisasi controller dengan dependency service.
     */
    public function __construct(
        protected AhspToolService $ahspToolService,
        protected ToolService $toolService
    ) {}

    /**
     * Menyimpan data alat ke dalam AHSP.
     *
     * Catatan:
     * - Validasi dilakukan di FormRequest
     * - DomainException untuk error bisnis
     */
    public function store(AhspToolStoreRequest $request)
    {
        try {
            $this->ahspToolService->createAhspTool($request->validated());

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
     * Memperbarui data alat pada AHSP.
     *
     * Catatan:
     * - Error bisnis dan error sistem dipisahkan
     */
    public function update(AhspToolUpdateRequest $request, $id)
    {
        try {
            $this->ahspToolService->updateAhspTool($id, $request->validated());

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
     * Menghapus alat dari AHSP.
     */
    public function destroy($id)
    {
        try {
            $this->ahspToolService->deleteAhspTool($id);

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
