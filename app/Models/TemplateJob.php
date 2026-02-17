<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * @property string $id
 * @property string $category_id
 * @property string $ahsp_id
 * @property string $work_name
 * @property string $unit
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */

class TemplateJob extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'template_jobs';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'category_id',
        'ahsp_id',
        'work_name',
        'unit'
    ];

    public function categoryJob()
    {
        return $this->belongsTo(CategoryJob::class, 'category_id');
    }
    public function ahsp()
    {
        return $this->belongsTo(Ahsp::class, 'ahsp_id');
    }
}
