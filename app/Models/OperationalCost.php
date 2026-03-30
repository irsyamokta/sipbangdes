<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * @property string $id
 * @property string $project_id
 * @property string $name
 * @property string $unit
 * @property float $volume
 * @property float $unit_price
 * @property float $total
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */

class OperationalCost extends Model
{
    use HasUuids;

    protected $table = 'operational_costs';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'project_id',
        'name',
        'unit',
        'volume',
        'unit_price',
        'total',
    ];

    protected $casts = [
        'volume' => 'float',
        'unit_price' => 'float',
        'total' => 'float',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
