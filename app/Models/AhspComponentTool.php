<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * @property string $id
 * @property string $ahsp_id
 * @property string $tool_id
 * @property float $coefficient
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class AhspComponentTool extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'ahsp_component_tools';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ahsp_id',
        'tool_id',
        'coefficient',
    ];

    protected $casts = [
        'coefficient' => 'float',
    ];

    public function ahsp()
    {
        return $this->belongsTo(Ahsp::class, 'ahsp_id');
    }

    public function masterTool()
    {
        return $this->belongsTo(MasterTool::class, 'tool_id');
    }
}
