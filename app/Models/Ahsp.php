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

    protected $appends = [
        'material_total',
        'wage_total',
        'tool_total',
        'subtotal',
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

    public function workerItems()
    {
        return $this->hasMany(WorkerItem::class, 'ahsp_id');
    }

    public function getMaterialTotalAttribute()
    {
        return $this->ahspComponentMaterials->map(function ($item) {
            return $item->coefficient * ($item->masterMaterial->price ?? 0);
        })->sum();
    }

    public function getWageTotalAttribute()
    {
        return $this->ahspComponentWages->map(function ($item) {
            return $item->coefficient * ($item->masterWage->price ?? 0);
        })->sum();
    }

    public function getToolTotalAttribute()
    {
        return $this->ahspComponentTools->map(function ($item) {
            return $item->coefficient * ($item->masterTool->price ?? 0);
        })->sum();
    }

    public function getSubtotalAttribute()
    {
        return $this->material_total + $this->wage_total + $this->tool_total;
    }
}
