<?php

namespace App\Modules\WorkerCategory\Controllers;

use Throwable;
use DomainException;
use App\Http\Controllers\Controller;
use App\Modules\WorkerCategory\Services\WorkerItemService;
use App\Modules\WorkerCategory\Requests\WorkerItemStoreRequest;
use App\Modules\WorkerCategory\Requests\WorkerItemUpdateRequest;

class WorkerItemController extends Controller
{
    /**
     * Inisialisasi controller dengan dependency service.
     */
    public function __construct(
        private WorkerItemService $workerItemService
    ) {}

    /**
     * Menyimpan item pekerjaan baru.
     *
     * Catatan:
     * - Validasi dilakukan di FormRequest
     * - DomainException untuk error bisnis (duplikasi nama)
     */
    public function store(WorkerItemStoreRequest $request)
    {
        try {
            $this->workerItemService->createWorkerItem($request->validated());

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
     * Memperbarui item pekerjaan.
     *
     * Catatan:
     * - Error bisnis dan error sistem dipisahkan
     */
    public function update(WorkerItemUpdateRequest $request, $id)
    {
        try {
            $this->workerItemService->updateWorkerItem($id, $request->validated());

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
     * Menghapus item pekerjaan.
     */
    public function destroy($id)
    {
        try {
            $this->workerItemService->deleteWorkerItem($id);

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
