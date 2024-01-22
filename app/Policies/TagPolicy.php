<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\Tag;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class TagPolicy
{

    public function viewAll(): bool
    {
        return (Auth::check() && Auth::user()->is_admin);
    }

    public function view(?Member $member, Tag $tag)
    {
        if ($tag->is_banned) {
            if ($member && $member->is_admin)
                return Response::allow();
            else
                return Response::deny('You cannot view a banned tag.');
        }

        return Response::allow();
    }
    
    public function follow(Member $member, Tag $tag)
    {
        if (!Auth::check()) {
            return Response::deny('You must be logged in to follow a tag.');
        }

        if ($tag->is_banned) {
            return Response::deny('You cannot follow a banned tag.');
        }

        return (!$member->followsTag($tag->id))
            ? Response::allow()
            : Response::deny('You are already following this tag.'
            );
    }

    public function unfollow(Member $member, Tag $tag)
    {
        if (!Auth::check()) {
            return Response::deny('You must be logged in to follow a tag.');
        }

        return ($member->followsTag($tag->id))
            ? Response::allow()
            : Response::deny('You are not following this tag.'
            );
    }

    public function update()
    {
        return (Auth::user()->is_admin)
            ? Response::allow()
            : Response::deny('You are not an admin. Only admins can update tags.'
            );
    }
}
