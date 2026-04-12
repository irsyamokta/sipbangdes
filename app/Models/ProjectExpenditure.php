<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * @property string $id
 * @property string $project_id
 * @property string $description
 * @property float $nominal
 * @property \Carbon\Carbon $date
 * @property string|null $information
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class ProjectExpenditure extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'project_expenditures';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'project_id',
        'description',
        'nominal',
        'date',
        'information',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'date' => 'date:Y-m-d',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
