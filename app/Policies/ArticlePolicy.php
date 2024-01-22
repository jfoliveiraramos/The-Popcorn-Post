<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;

class ArticlePolicy
{
    public function create(Member $member): bool
    {
        return !$member->is_blocked && !$member->is_deleted;
    }

    public function edit(Member $member, Article $article): bool
    {
        if ($article->contentItem->is_deleted) {
            return false;
        }
        return (($member->id === $article->author()->id && !$member->is_blocked && !$member->is_deleted) || $member->is_admin);
    }

    public function update(Member $member, Article $article): bool
    {
        if ($article->contentItem->is_deleted) {
            return false;
        }
        return (($member->id === $article->author()->id && !$member->is_blocked && !$member->is_deleted) || $member->is_admin);
    }

    public function delete(Member $member, Article $article): bool
    {
        if ($article->contentItem->is_deleted) {
            return false;
        }
        if (($article->comments_count() > 0 || $article->contentItem->votes_count() > 0) && !$member->is_admin) {
            return false;
        }
        return (($member->id === $article->author()->id && !$member->is_blocked && !$member->is_deleted) || $member->is_admin);
    }

    public function getComments(?Member $member, Article $article): bool
    {
        return !$article->contentItem->is_deleted;
    }
}
