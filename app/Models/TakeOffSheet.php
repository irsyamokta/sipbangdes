<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * @property string $id
 * @property string $project_id
 * @property string|null $ahsp_id
 * @property string|null $job_category_id
 * @property string $work_name
 * @property string $unit
 * @property float $volume
 * @property float|null $locked_unit_price
 * @property \Carbon\Carbon|null $locked_at
 * @property string|null $note
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class TakeOffSheet extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'take_off_sheets';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'project_id',
        'ahsp_id',
        'job_category_id',
        'work_name',
        'volume',
        'unit',
        'note',
        'locked_unit_price',
        'locked_at',
    ];

    protected $casts = [
        'volume' => 'float',
        'locked_unit_price' => 'float',
        'locked_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function ahsp()
    {
        return $this->belongsTo(Ahsp::class, 'ahsp_id');
    }

    public function categoryJob()
    {
        return $this->belongsTo(CategoryJob::class, 'job_category_id');
    }
}
