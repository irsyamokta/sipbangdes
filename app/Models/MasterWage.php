<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * @property string $id
 * @property string $code
 * @property string $position
 * @property string $unit
 * @property float $price
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class MasterWage extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_wages';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'code',
        'position',
        'unit',
        'price',
    ];

    protected $casts = [
        'price' => 'float',
    ];

    public function ahspComponentWages()
    {
        return $this->hasMany(AhspComponentWage::class, 'wage_id');
    }
}
