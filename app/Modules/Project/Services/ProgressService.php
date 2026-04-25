<?php

namespace App\Modules\Project\Services;

use App\Modules\Project\Repositories\ProgressRepository;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProgressService
{
    /**
     * Inisialisasi service dengan dependency repository.
     */
    public function __construct(
        protected ProgressRepository $progressRepository
    ) {}

    /**
     * Mengambil detail project beserta total progress.
     *
     * Catatan:
     * - Menggabungkan beberapa sumber data menjadi response siap pakai
     */
    public function getDetail(string $projectId): array
    {
        $project = $this->progressRepository->getProjectDetail($projectId);
        $total = $this->progressRepository->getTotalProgress($projectId);

        return [
            'project' => $project,
            'totalProgress' => $total,
        ];
    }

    /**
     * Membuat progress baru beserta upload dokumen.
     *
     * Aturan:
     * - Setiap file akan di-upload ke Cloudinary
     * - Jika salah satu upload gagal, seluruh proses dibatalkan (rollback)
     *
     * Catatan:
     * - Menggunakan transaction untuk menjaga konsistensi data
     * - Manual rollback Cloudinary dilakukan untuk menghindari orphan file
     */
    public function createWithDocuments(array $data, array $files = [])
    {
        return DB::transaction(function () use ($data, $files) {

            $progress = $this->progressRepository->createProgress($data);

            $uploadedPublicIds = [];

            try {
                foreach ($files as $file) {
                    $uploaded = Cloudinary::uploadApi()->upload(
                        $file->getRealPath(),
                        [
                            'folder' => 'projects/progress',
                        ]
                    );

                    $uploadedPublicIds[] = $uploaded['public_id'];

                    $progress->documents()->create([
                        'project_id' => $data['project_id'],
                        'uploaded_by' => $data['reported_by'],
                        'image_url' => $uploaded['secure_url'],
                        'public_id' => $uploaded['public_id'],
                    ]);
                }
            } catch (Throwable $e) {
                foreach ($uploadedPublicIds as $publicId) {
                    Cloudinary::uploadApi()->destroy($publicId);
                }
                throw $e;
            }

            return $progress->load('documents');
        });
    }

    /**
     * Memperbarui data progress yang sudah ada
     * serta menambahkan dokumen baru jika tersedia.
     *
     * Aturan:
     * - Data utama progress diperbarui terlebih dahulu
     * - Dokumen baru akan di-upload ke Cloudinary
     * - Jika terjadi kegagalan upload, seluruh perubahan dibatalkan
     *
     * Catatan:
     * - Menggunakan transaction untuk menjaga konsistensi data
     * - Manual rollback Cloudinary dilakukan jika upload gagal
     * - Dokumen lama tidak dihapus dalam proses ini
     */
    public function updateProgress(string $progressId, array $data, array $files = [])
    {
        return DB::transaction(function () use ($progressId, $data, $files) {

            $progress = $this->progressRepository->updateProgress(
                $progressId,
                ['description' => $data['description']]
            );

            if (! empty($files)) {
                $uploadedPublicIds = [];

                try {
                    foreach ($files as $file) {
                        $uploaded = Cloudinary::uploadApi()->upload(
                            $file->getRealPath(),
                            ['folder' => 'projects/progress']
                        );

                        $uploadedPublicIds[] = $uploaded['public_id'];

                        $progress->documents()->create([
                            'project_id' => $progress->project_id,
                            'uploaded_by' => auth()->id(),
                            'image_url' => $uploaded['secure_url'],
                            'public_id' => $uploaded['public_id'],
                        ]);
                    }
                } catch (Throwable $e) {
                    foreach ($uploadedPublicIds as $publicId) {
                        Cloudinary::uploadApi()->destroy($publicId);
                    }
                    throw $e;
                }
            }

            return $progress->load('documents');
        });
    }

    /**
     * Menghapus dokumen progress dari sistem.
     *
     * Aturan:
     * - File dihapus terlebih dahulu dari Cloudinary
     * - Data dokumen dihapus dari database setelah file berhasil dihapus
     *
     * Catatan:
     * - Menggunakan transaction untuk menjaga konsistensi data
     * - Mencegah terjadinya orphan data atau orphan file
     */
    public function deleteDocument(string $documentId)
    {
        return DB::transaction(function () use ($documentId) {

            $document = $this->progressRepository->getDocument($documentId);

            Cloudinary::uploadApi()->destroy($document->public_id);

            $document->delete();

            return true;
        });
    }
}
