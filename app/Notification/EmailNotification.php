<?php
/**
 * Created by PhpStorm.
 * User: Tabares
 * Date: 19/12/2025
 * Time: 8:52
 */

namespace App\Notification;


use Illuminate\Support\Facades\Notification;

class EmailNotification implements NotificationSender
{
    /**
     * @param $message
     */
    public function send($message)
  {
      $user=$user = User::find(1);
      Notification::sendNow($user,$message,['mail']);
  }
}