<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\Notification;
use Illuminate\Auth\Access\Response;

class NotificationPolicy
{
   public function getNotifications(Member $member, string $username)
   {
      return $member->username == $username;
   }

   public function toggleRead(Member $member, Notification $notification)
   {
      return $member->id == $notification->notified_id || $member->is_admin;
   }

   public function markAllAsRead(Member $member, string $username)
   {
      return $member->username == $username || $member->is_admin;
   }
}
