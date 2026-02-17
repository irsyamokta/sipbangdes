<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * @property string $id
 * @property string $project_id
 * @property string|null $progress_id
 * @property string $uploaded_by
 * @property string $image_url
 * @property string $public_id
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class ProjectDocument extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'project_documents';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'project_id',
        'progress_id',
        'uploaded_by',
        'image_url',
        'public_id',
        'description',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function projectProgress()
    {
        return $this->belongsTo(ProjectProgress::class, 'progress_id');
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
