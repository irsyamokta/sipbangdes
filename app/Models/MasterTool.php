<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * @property string $id
 * @property string $code
 * @property string $name
 * @property string $unit
 * @property float $price
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class MasterTool extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_tools';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'code',
        'name',
        'unit',
        'price',
    ];

    protected $casts = [
        'price' => 'float',
    ];

    public function ahspComponentTools()
    {
        return $this->hasMany(AhspComponentTool::class, 'tool_id');
    }
}
