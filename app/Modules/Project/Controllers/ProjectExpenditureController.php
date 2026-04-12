<?php

namespace App\Modules\Project\Controllers;

use Throwable;
use DomainException;
use App\Http\Controllers\Controller;
use App\Modules\Project\Services\ProjectExpenditureService;
use App\Modules\Project\Requests\ProjectExpenditureStoreRequest;
use App\Modules\Project\Requests\ProjectExpenditureUpdateRequest;

class ProjectExpenditureController extends Controller
{
    /**
     * Inisialisasi controller dengan dependency service.
     */
    public function __construct(
        protected ProjectExpenditureService $service
    ) {}

    /**
     * Menyimpan data expenditure baru.
     *
     * Catatan:
     * - Validasi dilakukan di FormRequest
     * - DomainException digunakan untuk error bisnis (anggaran)
     */
    public function store(ProjectExpenditureStoreRequest $request, string $projectId)
    {
        try {
            $this->service->create([...$request->validated(), 'project_id' => $projectId]);

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                'nominal' => $e->getMessage()
            ]);
        } catch (Throwable $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi'
            ]);
        }
    }

    /**
     * Memperbarui data expenditure.
     *
     * Catatan:
     * - Error bisnis dan error sistem dipisahkan
     */
    public function update(ProjectExpenditureUpdateRequest $request, string $id)
    {
        try {
            $this->service->update($id, $request->validated());

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                'nominal' => $e->getMessage()
            ]);
        } catch (Throwable $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi'
            ]);
        }
    }

    /**
     * Menghapus data expenditure.
     *
     * Catatan:
     * - Data dihapus secara langsung
     */
    public function destroy(string $id)
    {
        try {
            $this->service->delete($id);

            return back();
        } catch (Throwable $e) {
            return back()->withErrors([
                'Terjadi kesalahan saat menghapus data'
            ]);
        }
    }
}
