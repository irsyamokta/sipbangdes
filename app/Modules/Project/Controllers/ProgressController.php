<?php

namespace App\Modules\Project\Controllers;

use Throwable;
use DomainException;
use App\Http\Controllers\Controller;
use App\Modules\Project\Services\ProgressService;
use App\Modules\Project\Requests\ProgressStoreRequest;

class ProgressController extends Controller
{
    /**
     * Inisialisasi controller dengan dependency service.
     */
    public function __construct(
        protected ProgressService $progressService
    ) {}

    /**
     * Menyimpan progress baru beserta dokumen.
     *
     * Catatan:
     * - Validasi dilakukan di FormRequest
     * - User login digunakan sebagai 'reported_by'
     * - Error bisnis dan sistem dipisahkan
     */
    public function storeProgress(ProgressStoreRequest $request, string $id)
    {
        try {
            $this->progressService->createWithDocuments(
                data: [
                    ...$request->validated(),
                    'project_id' => $id,
                    'reported_by' => auth()->id(),
                ],
                files: $request->file('documents', [])
            );

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
