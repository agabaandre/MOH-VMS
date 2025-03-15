<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Employee\Database\factories\EmployeeFactory;
use Modules\VehicleManagement\Entities\Facility;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_code',
        'name',
        'department_id',
        'position_id',
        'nid',
        'card_number',
        'phone',
        'email',
        'blood_group',
        'dob',
        'present_contact',
        'present_address',
        'present_city',
        'contact_person_name',
        'contact_person_mobile',
        'reference_name',
        'reference_email',
        'reference_address',
        'avatar_path',
        'facility_id', // Added facility_id to ensure it's fillable
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::created(function ($employee) {
            $employee->employee_code = 'EMP'.\str_pad($employee->id, 4, '0', STR_PAD_LEFT);
            $employee->save();
        });
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }
    
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    protected static function newFactory()
    {
        return EmployeeFactory::new();
    }

    /**
     * Get the file url attribute.
     *
     * @return ?string
     */
    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar_path ? storage_asset($this->avatar_path) : null;
    }
}
