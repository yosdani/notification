<?php
/**
 * Created by PhpStorm.
 * User: Tabares
 * Date: 19/12/2025
 * Time: 8:50
 */

namespace App\Notification;


class SmsNotification implements NotificationSender
{
    /**
     * @param $message
     */
    public function send($message)
  {
      $user=$user = User::find(1);
      Notification::sendNow($user,$message,['vonage']);
  }
}