<?php

namespace Modules\VehicleManagement\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Employee\Entities\Department;
use Modules\Employee\Entities\Driver;
use Modules\Inventory\Entities\Vendor;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'department_id',
        'registration_date',
        'license_plate',
        'previous_plate',
        'ownership_id',
        'vehicle_type_id',
        'vehicle_division_id',
        'rta_circle_office_id',
        'driver_id',
        'vendor_id',
        'seat_capacity',
        'purchase_value',
        'is_active',
        'off_board_date',
        'off_board_remarks',
        'off_board_lot_number',
        'off_board_buyer',
        'off_board_amount',
        'off_board_reason'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'off_board_date' => 'date',
    ];

    protected static function newFactory()
    {
        return \Modules\VehicleManagement\Database\factories\VehicleFactory::new();
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($vehicle) {
            $vehicle->name = $vehicle->generateName();
        });
    }

    protected function generateName()
    {
        $type = $this->vehicle_type ? $this->vehicle_type->name : 'Unknown Type';
        return "{$this->license_plate} - {$type}";
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function vehicle_type()
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function ownership()
    {
        return $this->belongsTo(VehicleOwnershipType::class);
    }

    public function vehicle_division()
    {
        return $this->belongsTo(VehicleDivision::class);
    }

    public function rta_circle_office()
    {
        return $this->belongsTo(Facility::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
