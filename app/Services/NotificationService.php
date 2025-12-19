<?php

namespace App\Services;

use App\Notification\NotificationSender;

class NotificationService
{
    /**
     * Create a new class instance.
     */
    protected $sender;
    public function __construct(NotificationSender $sender)
    {
        $this->senser = $sender;
    }

    public function notify($message){
        $this->senser->send($message);
    }
}
