<?php

namespace App\Traits;

use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

trait NotifiableModel
{
    protected static function bootNotifiableModel()
    {
        static::created(function ($model) {
            $notificationService = new NotificationService();
            $recipients = self::getNotificationRecipients();
            $notificationService->sendCreationNotification($model, $recipients);
        });
    }

    public static function getNotificationRecipients()
    {
        $recipients = [];

        // Get all users with Administrator role
        $administrators = User::role('Administrator')->get();
        foreach ($administrators as $admin) {
            $recipients[] = $admin->email;
        }

        // Add currently authenticated user if exists
        if (Auth::check()) {
            $recipients[] = Auth::user()->email;
        }

        // Remove duplicates and return
        return array_unique($recipients);
    }

    public function sendApprovalNotification()
    {
        $notificationService = new NotificationService();
        $recipients = self::getNotificationRecipients();
        $notificationService->sendApprovalNotification($this, $recipients);
    }
}
