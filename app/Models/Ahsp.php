<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $work_code
 * @property string $work_name
 * @property string $unit
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method bool|null delete()
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
        return $this->ahspComponentMaterials()
            ->join('master_materials', 'master_materials.id', '=', 'ahsp_component_materials.material_id')
            ->selectRaw('SUM(ahsp_component_materials.coefficient * master_materials.price) as total')
            ->value('total') ?? 0;
    }

    public function getWageTotalAttribute()
    {
        return $this->ahspComponentWages()
            ->join('master_wages', 'master_wages.id', '=', 'ahsp_component_wages.wage_id')
            ->selectRaw('SUM(ahsp_component_wages.coefficient * master_wages.price) as total')
            ->value('total') ?? 0;
    }

    public function getToolTotalAttribute()
    {
        return $this->ahspComponentTools()
            ->join('master_tools', 'master_tools.id', '=', 'ahsp_component_tools.tool_id')
            ->selectRaw('SUM(ahsp_component_tools.coefficient * master_tools.price) as total')
            ->value('total') ?? 0;
    }

    public function getSubtotalAttribute()
    {
        return $this->material_total + $this->wage_total + $this->tool_total;
    }
}
