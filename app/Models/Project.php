<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * @property string $id
 * @property string $project_name
 * @property string $location
 * @property int $budget_year
 * @property string $project_status
 * @property string $rab_status
 * @property \Carbon\Carbon|null $submitted_at
 * @property string|null $submitted_by
 * @property \Carbon\Carbon|null $approved_at
 * @property string|null $approved_by
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Project extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'projects';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'project_name',
        'location',
        'budget_year',
        'project_status',
        'rab_status',
        'submitted_at',
        'submitted_by',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'budget_year' => 'integer',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function takeOffSheets()
    {
        return $this->hasMany(TakeOffSheet::class, 'project_id');
    }

    public function projectProgresses()
    {
        return $this->hasMany(ProjectProgress::class, 'project_id');
    }

    public function projectDocuments()
    {
        return $this->hasMany(ProjectDocument::class, 'project_id');
    }

    public function rabAiInsights()
    {
        return $this->hasMany(RabAiInsight::class, 'project_id');
    }

    public function rabComments()
    {
        return $this->hasMany(RabComment::class, 'project_id');
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
