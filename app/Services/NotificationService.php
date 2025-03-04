<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\ApprovalNotification;
use App\Mail\CreationNotification;
use App\Mail\WelcomeNotification;

class NotificationService
{
    public function sendCreationNotification($model, $recipients)
    {
        Mail::to($recipients)->send(new CreationNotification($model));
    }

    public function sendApprovalNotification($model, $recipients)
    {
        Mail::to($recipients)->send(new ApprovalNotification($model));
    }

    public function sendWelcomeNotification($user)
    {
        Mail::to($user->email)->send(new WelcomeNotification($user));
    }
}
