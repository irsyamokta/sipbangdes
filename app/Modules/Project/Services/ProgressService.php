<?php

namespace App\Modules\Project\Services;

use Throwable;
use App\Modules\Project\Repositories\ProgressRepository;
use Illuminate\Support\Facades\DB;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProgressService
{
    /**
     * Inisialisasi service dengan dependency repository.
     */
    public function __construct(
        protected ProgressRepository $repo
    ) {}

    /**
     * Mengambil detail project beserta total progress.
     *
     * Catatan:
     * - Menggabungkan beberapa sumber data menjadi response siap pakai
     */
    public function getDetail(string $projectId): array
    {
        $project = $this->repo->getProjectDetail($projectId);
        $total = $this->repo->getTotalProgress($projectId);

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

            $progress = $this->repo->createProgress($data);

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
                        'image_url'   => $uploaded['secure_url'],
                        'public_id'   => $uploaded['public_id'],
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
}
