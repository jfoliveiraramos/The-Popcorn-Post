<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\TopicSuggestion;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;


class TopicSuggestionPolicy
{

    public function viewAll(): bool
    {
        return (Auth::check() && Auth::user()->is_admin);
    }

    public function create(): bool
    {
        return (Auth::check());
    }

    public function update(): bool {
        return (Auth::check() && Auth::user()->is_admin);
    }
}
