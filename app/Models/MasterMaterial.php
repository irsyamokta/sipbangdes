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
class MasterMaterial extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_materials';
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

    public function ahspComponentMaterials()
    {
        return $this->hasMany(AhspComponentMaterial::class, 'material_id');
    }
}
