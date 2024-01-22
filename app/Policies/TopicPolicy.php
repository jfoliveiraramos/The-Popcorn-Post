<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;

class TopicPolicy
{
    public function viewAll(): bool
    {
        return (Auth::check() && Auth::user()->is_admin);
    }

    public function update(Member $member, Topic $topic): bool
    {
        return ($topic->id !== 0 && $member->is_admin);
    }

    public function delete(Member $member, Topic $topic): bool
    {
        return ($topic->id !== 0 && $member->is_admin);
    }
}
