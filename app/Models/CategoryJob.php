<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * @property string $id
 * @property string $name
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class CategoryJob extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'category_jobs';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'description',
    ];

    public function takeOffSheets()
    {
        return $this->hasMany(TakeOffSheet::class, 'job_category_id');
    }

    public function templateJobs()
    {
        return $this->hasMany(TemplateJob::class, 'category_id');
    }
}
