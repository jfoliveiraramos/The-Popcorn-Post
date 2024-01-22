<?php

namespace App\Policies;

use App\Models\Member;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class MemberPolicy
{
    use HandlesAuthorization;

    public function view(Member $authenticated, Member $profile): bool
    {
        return (!$profile->is_deleted);
    }

    public function update(Member $account, Member $member): bool
    {
        if ($member->is_deleted) {
            return false;
        }

        if ($member->is_admin && ($account->id !== $member->id)) {
            return false;
        }

        return ($account->id === $member->id || $account->is_admin);
    }

    public function create(Member $account): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return ($account->is_admin);
    }

    public function promote(Member $authed, Member $account): bool
    {
        if (!Auth::check() || !$authed->is_admin || $account->is_admin || $account->is_deleted || $account->is_blocked) {
            return false;
        }

        return true;
    }

    public function block(Member $authed, Member $account): bool
    {
        if ($account->is_blocked || $account->is_deleted) {
            return false;
        }

        return ($authed->is_admin && !$account->is_admin);
    }

    public function unblock(Member $authed, Member $account): bool
    {
        if (!$account->is_blocked || $account->is_deleted) {
            return false;
        }

        return ($authed->is_admin && !$account->is_admin);
    }

    public function delete(Member $authed, Member $account): bool
    {
        if ($account->is_deleted) {
            return false;
        }

        return ($authed->is_admin && !$account->is_admin);
    }

    public function deleteOwnAccount(Member $account, Member $member): bool
    {
        if ($member->is_admin) {
            return false;
        }

        return ($account->is_admin || $account->id === $member->id);
    }

    public function manage(Member $account): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return ($account->is_admin);
    }

    public function follow(Member $authenticated, Member $member): bool
    {
        if ($member->is_deleted) {
            return false;
        }
        
        return ($authenticated->id !== $member->id && !$authenticated->isFollowing($member));
    }

    public function unfollow(Member $authenticated, Member $member): bool
    {
        if ($member->is_deleted) {
            return false;
        }

        return ($authenticated->id !== $member->id && $authenticated->isFollowing($member));
    }
}
