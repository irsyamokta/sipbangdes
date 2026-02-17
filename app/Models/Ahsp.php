<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * @property string $id
 * @property string $work_code
 * @property string $work_name
 * @property string $unit
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Ahsp extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'ahsps';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'work_code',
        'work_name',
        'unit',
    ];

    public function ahspComponentTools()
    {
        return $this->hasMany(AhspComponentTool::class, 'ahsp_id');
    }

    public function ahspComponentMaterials()
    {
        return $this->hasMany(AhspComponentMaterial::class, 'ahsp_id');
    }

    public function ahspComponentWages()
    {
        return $this->hasMany(AhspComponentWage::class, 'ahsp_id');
    }

    public function takeOffSheets()
    {
        return $this->hasMany(TakeOffSheet::class, 'ahsp_id');
    }

    public function templateJobs()
    {
        return $this->hasMany(TemplateJob::class, 'ahsp_id');
    }
}
