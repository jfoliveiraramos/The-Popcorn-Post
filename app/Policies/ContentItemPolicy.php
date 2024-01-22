<?php

namespace App\Policies;

use App\Models\ContentItem;
use App\Models\Member;

class ContentItemPolicy
{
    public function vote(Member $member, ContentItem $contentItem): bool
    {
        return !$member->is_blocked && !$member->is_deleted && !$contentItem->is_deleted && $contentItem->author->id != $member->id; 
    }
}
