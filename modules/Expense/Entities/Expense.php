<?php

namespace Modules\Expense\Entities;

use App\Traits\FormatTimestamps;
use App\Traits\GenerateCode;
use App\Traits\NotifiableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use FormatTimestamps;
    use GenerateCode;
    use HasFactory;
    use NotifiableModel;

    // ...existing code...

    /**
     * Update the status and send notification
     *
     * @param string $status
     * @return void
     */
    public function updateStatus($status)
    {
        $this->status = $status;
        $this->save();

        if (in_array($status, ['approved', 'rejected'])) {
            $this->sendApprovalNotification();
        }
    }

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($expense) {
            $expense->code = $expense->generateCode();
        });
    }

    // ...existing code...
}
