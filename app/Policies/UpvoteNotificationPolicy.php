<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\UpvoteNotification;
use Illuminate\Auth\Access\Response;

class UpvoteNotificationPolicy
{
    public function create(Member $member, String $notified_id): bool
    {
        return $member->id != $notified_id;
    }
}
