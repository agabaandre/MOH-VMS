<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApprovalNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function build()
    {
        $modelName = class_basename($this->model);
        return $this->view('emails.approval-notification')
                    ->subject("{$modelName} Approved")
                    ->with([
                        'modelType' => $modelName,
                        'data' => $this->model
                    ]);
    }
}
