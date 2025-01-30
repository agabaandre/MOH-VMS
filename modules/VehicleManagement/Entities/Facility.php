<?php

namespace Modules\VehicleManagement\Entities;

use App\Traits\FormatTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    // cast attributes
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
