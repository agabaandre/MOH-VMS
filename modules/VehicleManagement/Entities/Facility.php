<?php

namespace Modules\VehicleManagement\Entities;

use App\Traits\FormatTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Employee\Entities\Employee;

class Facility extends Model
{
    use FormatTimestamps,
        HasFactory;

    // set table name
    protected $table = 'facilities';

    // The mass assignable attributes.
    protected $fillable = [
        'name',
        'description',
        'is_active',
        'facility_id',
        'district',
        'region',
    ];

    // cast attributes
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the employees for the facility.
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
