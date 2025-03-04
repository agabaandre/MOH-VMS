<?php

namespace App\Models;

use App\Traits\NotifiableModel;

class VehicleRequisition extends Model
{
    use NotifiableModel;
    
    // ...existing code...

    public function approve()
    {
        // ...existing approval logic...
        $this->sendApprovalNotification();
    }
}
