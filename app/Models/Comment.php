<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $incrementing = false;
    
    public $timestamps  = false;

    protected $table = 'comment';

    protected $fillable = [
        'id',
        'article_id',
        'reply_id',
        'is_reply',
    ];

    public function contentItem() {
        return $this->belongsTo(ContentItem::class, 'id', 'id');
    }

    public function article() {
        return $this->belongsTo(Article::class, 'article_id', 'id');
    }

    public function replies() {
        return $this->hasMany(Comment::class, 'reply_id', 'id');
    }

    public function replyTo() {
        return $this->belongsTo(Comment::class, 'reply_id', 'id');
    }

    public function author() {
        return $this->contentItem->author;
    }

    public function body() {
        return $this->contentItem->body;
    }

    public function score(){
        return $this->contentItem->academy_score;
    }

    public function date() {
        $dateTime = new DateTime($this->contentItem->date_time);
        return $dateTime->format('d/m/Y');
    }

    public function time() {
        $dateTime = new DateTime($this->contentItem->date_time);
        return $dateTime->format('H:i');
    }

    public function date_time() {
        return $this->contentItem->date_time;
    }

    public function replies_count() {
        return $this->replies()->whereHas('contentItem', function ($query) {
            $query->where('is_deleted', false);
        })->count();
    }

    public function votes_count() {
        return $this->contentItem->votes_count();
    }

    public function preview() {
        $body = $this->body();

        if (strlen($body) > 300) {
            $lastSpace = strrpos(substr($body, 0, 300), ' ');
            return substr($body, 0, $lastSpace) . '...';
        } else {
            return $body;
        }
    }

    public static function search($query, $exactMatch, $time, $minScore, $maxScore, $topic, $sort, $page = 1) {
        $perPage = 10; 

        $contentItems = ContentItem::searchQuery($query, $exactMatch, $time, $minScore, $maxScore, $topic, $sort);

        $comments = Comment::whereIn('comment.id', $contentItems->pluck('id'))->join('content_item', 'comment.id', '=', 'content_item.id');

        switch($sort) {
            case 'highest_score':
                $comments = $comments->orderBy('academy_score', 'DESC');
                break;
            case 'lowest_score':
                $comments = $comments->orderBy('academy_score', 'ASC');
                break;
            case 'newest':
                $comments = $comments->orderBy('date_time', 'DESC');
                break;
            case 'oldest':
                $comments = $comments->orderBy('date_time', 'ASC');
                break;
        }

        $comments = $comments->paginate($perPage, ['*'], 'page', $page);

        return $comments;
    }

    public function canEdit()
    {
        if (!auth()->check()) {
            return false;
        }
        if ($this->contentItem->is_deleted) {
            return false;
        }
        $author = $this->contentItem->author->id == auth()->user()->id;
        $admin = auth()->user()->is_admin;
        return $author || $admin;
    }

    public function canDelete()
    {
        if (!auth()->check()) {
            return false;
        }
        if ($this->contentItem->is_deleted) {
            return false;
        }
        $count = $this->contentItem->votes_count() > 0 || $this->replies_count() > 0;
        $author = $this->contentItem->author->id == auth()->user()->id;
        $admin = auth()->user()->is_admin;
        return ($author && !$count) || $admin;
    }
}
