<?php

namespace App\Modules\Project\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Project\Requests\ProgressStoreRequest;
use App\Modules\Project\Requests\ProgressUpdateRequest;
use App\Modules\Project\Services\ProgressService;
use DomainException;
use Throwable;

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
                $e->getMessage(),
            ]);
        } catch (Throwable $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi',
            ]);
        }
    }

    /**
     * Memperbarui data progress yang sudah ada,
     * termasuk penambahan dokumen baru jika tersedia.
     *
     * Catatan:
     * - Validasi input dilakukan melalui ProgressUpdateRequest
     * - Dokumen baru akan ditambahkan tanpa menghapus dokumen lama
     *   (kecuali dihapus melalui endpoint deleteDocument)
     * - Error bisnis ditangani menggunakan DomainException
     * - Error sistem umum ditangani menggunakan Throwable
     */
    public function updateProgress(ProgressUpdateRequest $request, string $progressId)
    {
        try {
            $this->progressService->updateProgress(
                progressId: $progressId,
                data: $request->validated(),
                files: $request->file('documents', [])
            );

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                $e->getMessage(),
            ]);
        } catch (Throwable $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi',
            ]);
        }
    }

     /**
     * Menghapus dokumen yang terhubung dengan progress.
     *
     * Catatan:
     * - Hanya dokumen yang dipilih yang akan dihapus
     * - Proses penghapusan termasuk data di database
     * - Jika terjadi kesalahan sistem, akan dikembalikan pesan error umum
     */
    public function deleteDocument(string $documentId)
    {
        try {
            $this->progressService
                ->deleteDocument(
                    documentId: $documentId
                );

            return back();
        } catch (Throwable $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi',
            ]);
        }
    }
}
