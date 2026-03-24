<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Queue\Worker;

/**
 * @property string $id
 * @property string $name
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class WorkerCategory extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'worker_categories';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'description',
    ];

    protected $appends = [
        'total_items'
    ];


    public function takeOffSheets()
    {
        return $this->hasMany(TakeOffSheet::class, 'worker_category_id');
    }

    public function workerItems()
    {
        return $this->hasMany(WorkerItem::class, 'category_id');
    }

    public function getTotalItemsAttribute()
    {
        return $this->workerItems()->count();
    }
}
