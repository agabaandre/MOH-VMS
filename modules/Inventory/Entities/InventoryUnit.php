<?php

namespace Modules\Inventory\Entities;

use App\Traits\FormatTimestamps;
use Illuminate\Database\Eloquent\Model;

class InventoryUnit extends Model
{
    use FormatTimestamps;

    protected $fillable = ['name', 'abbreviation', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function parts()
    {
        return $this->hasMany(InventoryParts::class, 'unit_id');
    }
}
