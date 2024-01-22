<?php

namespace App\Policies;

use App\Models\Appeal;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\Response;

class AppealPolicy
{
    public function create(Member $member)
    {
        return (Auth::check() && $member->is_blocked)
            ? Response::allow()
            : Response::deny('Only blocked members can create unblock appeals.');
    }

    public function handle(Member $member)
    {
        return (Auth::check() && $member->is_admin)
            ? Response::allow()
            : Response::deny('Only admins can handle unblock appeals.');
    }

    public function getAppeals(Member $member)
    {
        return (Auth::check() && $member->is_admin)
            ? Response::allow()
            : Response::deny('Only admins can view unblock appeals.');
    }
}
