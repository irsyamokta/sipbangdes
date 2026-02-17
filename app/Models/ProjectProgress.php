<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * @property string $id
 * @property string $project_id
 * @property string $reported_by
 * @property float $percentage
 * @property string $description
 * @property \Carbon\Carbon $created_at
 */
class ProjectProgress extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'project_progresses';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'project_id',
        'reported_by',
        'percentage',
        'description',
    ];

    protected $casts = [
        'percentage' => 'float',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
