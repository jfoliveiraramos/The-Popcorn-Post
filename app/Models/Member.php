<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class Member extends Authenticatable
{
    use HasFactory, Notifiable;

    public $timestamps = false;

    protected $table = 'member';

    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'password',
        'biography',
        'google_id'
    ];

    protected $hidden = [
        'password',
        'is_blocked',
        'is_admin',
        'is_deleted',
        'profile_image_id',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function profile_image() {
        return $this->hasOne(ProfileImage::class, 'id', 'profile_image_id');
    }

    public function content_items() {
        return $this->hasMany(ContentItem::class, 'author_id');
    }

    public function articles() {
        $contentItems = $this->content_items();
        $articles = Article::whereIn('id', $contentItems->pluck('id'))->get();
        return $articles;
    }

    public function comments() {
        $contentItems = $this->content_items();
        $comments = Comment::whereIn('id', $contentItems->pluck('id'))->get();
        return $comments;
    }

    public function followers() {
        return $this->belongsToMany(Member::class, 'follow_member', 'followed_id', 'follower_id');
    }

    public function following() {
        return $this->belongsToMany(Member::class, 'follow_member', 'follower_id', 'followed_id');
    }

    public function tags() {
        return $this->belongsToMany(Tag::class, 'follow_tag', 'member_id', 'tag_id');
    }

    public function notifications() {
        return $this->hasMany(Notification::class,'notified_id', 'id');
    }

    public function votes() {
        return $this->belongsToMany(ContentItem::class, 'vote', 'member_id', 'content_item_id')->withPivot('weight');
    }

    public function name() {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function hasUpvoted($contentItemId) {   
        $upVote = $this->votes()->where('content_item_id', $contentItemId)->where('weight', 1)->first();
        return $upVote !== null;
    }

    public function hasDownvoted($contentItemId) {
        $downVote = $this->votes()->where('content_item_id', $contentItemId)->where('weight', -1)->first();
        return $downVote !== null;
    }
    
    public function articles_count() {
        $contentItems = $this->content_items()->where('is_deleted', false);
        $articles = Article::whereIn('id', $contentItems->pluck('id'))->get();
        return $articles->count();
    }

    public function comments_count() {
        $contentItems = $this->content_items()->where('is_deleted', false);
        $comments = Comment::whereIn('id', $contentItems->pluck('id'))->get();
        return $comments->count();
    }

    public function followers_count() {
        return $this->followers()->where('is_blocked', false)->where('is_deleted', false)->count();
    }

    public function following_count() {
        return $this->following()->where('is_blocked', false)->where('is_deleted', false)->count();
    }

    public static function search($query, $exactMatch, $sort, $page = 1) {

        $perPage = 10;

        $members = Member::where('is_deleted', false);

        switch($sort) {
            case 'highest_score':
                $members = $members->orderBy('academy_score', 'DESC');
                break;
            case 'lowest_score':
                $members = $members->orderBy('academy_score', 'ASC');
                break;
        }

        if (!$query) {
            return $members->paginate($perPage, ['*'], 'page', $page);
        }
        else if ($exactMatch) {
            $members = $members->where('username', '=', "$query");
        } else {
            $terms = explode(' ', $query);
            $searchTerms = implode(' | ', $terms); 
            $members = $members->whereRaw("tsvectors @@ to_tsquery('english', ?)", [$searchTerms]);
        }
        return $members->paginate($perPage, ['*'], 'page', $page);
    }

    public function canEditProfile() {
        $account = Auth::user();
        return ($account->id === $this->id || $account->is_admin);
    }

    public static function getAllMembers() {
        return Member::where('is_deleted', false)->get();
    }

    public function isFollowing(Member $member) {
        return $this->following()->where('followed_id', $member->id)->exists();
    }

    public function followsTag(Int $tagId) {
        return $this->tags()->where('tag_id', $tagId)->exists();
    }

    public function appeals() {
        return $this->hasMany(Appeal::class, 'submitter_id', 'id');
    }
}
