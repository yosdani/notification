<?php

namespace App\Notification;

interface NotificationSender
{
    //
    /**
     * @param $message
     * @return mixed
     */
    public  function send($message);
}
