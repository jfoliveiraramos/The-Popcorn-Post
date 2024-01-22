<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\Member;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class CommentPolicy
{
    public function create(Member $member): bool
    {
        return !$member->is_blocked && !$member->is_deleted;
    }

    public function delete(Member $member, Comment $comment): bool
    {
        if ($comment->contentItem->is_deleted) {
            return false;
        }
        return ($member->id == $comment->author()->id && $comment->votes_count() == 0 && $comment->replies_count() == 0 
        && !$member->is_blocked && !$member->is_deleted) || $member->is_admin;
    }

    public function update(Member $member, Comment $comment): bool
    {
        if ($comment->contentItem->is_deleted) {
            return false;
        }
        return ($member->id == $comment->author()->id && !$member->is_blocked && !$member->is_deleted) || $member->is_admin;
    }
}
