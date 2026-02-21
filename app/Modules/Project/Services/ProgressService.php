<?php

namespace App\Modules\Project\Services;

use App\Modules\Project\Repositories\ProgressRepository;
use Illuminate\Support\Facades\DB;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProgressService
{
    public function __construct(
        protected ProgressRepository $repo
    ) {}

    public function getDetail(string $projectId): array
    {
        $project = $this->repo->getProjectDetail($projectId);
        $total = $this->repo->getTotalProgress($projectId);

        return [
            'project' => $project,
            'totalProgress' => $total,
        ];
    }

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
            } catch (\Throwable $e) {
                foreach ($uploadedPublicIds as $publicId) {
                    Cloudinary::uploadApi()->destroy($publicId);
                }
                throw $e;
            }

            return $progress->load('documents');
        });
    }
}
