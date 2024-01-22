<?php

namespace App\Models;

use DateTime;
use App\Models\ContentItem;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public $incrementing = false;

    public $timestamps  = false;

    protected $table = 'article';

    protected $fillable = [
        'id',
        'title',
        'topic_id',
    ];

    public function contentItem()
    {
        return $this->belongsTo(ContentItem::class, 'id', 'id');
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_article', 'article_id', 'tag_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'article_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(ArticleImage::class, 'article_id', 'id');
    }

    public function author()
    {
        return $this->contentItem->author;
    }

    public function body()
    {
        return $this->contentItem->body;
    }

    public function score()
    {
        return $this->contentItem->academy_score;
    }

    public function date()
    {
        $dateTime = new DateTime($this->contentItem->date_time);
        return $dateTime->format('d/m/Y');
    }

    public function time()
    {
        $dateTime = new DateTime($this->contentItem->date_time);
        return $dateTime->format('H:i');
    }

    public function date_time()
    {
        return $this->contentItem->date_time;
    }

    public function comments_count()
    {
        return $this->comments()->whereHas('contentItem', function ($query) {
            $query->where('is_deleted', false);
        })->count();
    }

    public function preview($size = 300)
    {
        $body = $this->body();

        if (strlen($body) > $size) {
            $lastSpace = strrpos(substr($body, 0, $size), ' ');
            return substr($body, 0, $lastSpace) . '...';
        } else {
            return $body;
        }
    }

    public static function search($query, $exactMatch, $time, $minScore, $maxScore, $topic, $sort, $page = 1)
    {
        $perPage = 10;

        $contentItems = ContentItem::searchQuery($query, $exactMatch, $time, $minScore, $maxScore, $topic, $sort);

        $articles = Article::whereIn('article.id', $contentItems->pluck('id'))->join('content_item', 'article.id', '=', 'content_item.id');

        switch ($sort) {
            case 'highest_score':
                // order by the respective joined content item's academy score
                $articles = $articles->orderBy('content_item.academy_score', 'DESC');
                break;
            case 'lowest_score':
                $articles = $articles->orderBy('content_item.academy_score', 'ASC');
                break;
            case 'newest':
                $articles = $articles->orderBy('content_item.date_time', 'DESC');
                break;
            case 'oldest':
                $articles = $articles->orderBy('content_item.date_time', 'ASC');
                break;
        }

        return $articles->paginate($perPage, ['*'], 'page', $page);
    }

    public function canEdit()
    {
        if (!auth()->check() || $this->contentItem->is_deleted) {
            return false;
        }
        $author = $this->contentItem->author->id == auth()->user()->id;
        $admin = auth()->user()->is_admin;
        return $author || $admin;
    }

    public function canDelete()
    {
        if (!auth()->check() || $this->contentItem->is_deleted) {
            return false;
        }
        $count = $this->contentItem->votes_count() > 0 || $this->comments_count() > 0;
        $author = $this->contentItem->author->id == auth()->user()->id;
        $admin = auth()->user()->is_admin;
        return ($author && !$count) || $admin;
    }

    public function directComments()
    {
        return $this->comments()->where('is_reply', false)->whereHas('contentItem', function ($query) {
            $query->where('is_deleted', false);
        });
    }

    public function relatedNews()
    {
        $relatedNews = Article::where('article.id', '!=', $this->id)
            ->join('content_item', 'article.id', '=', 'content_item.id')
            ->where('content_item.is_deleted', false)
            ->join('tag_article', 'article.id', '=', 'tag_article.article_id')
            ->whereIn('tag_article.tag_id', $this->tags->pluck('id'))
            ->orderBy('content_item.academy_score', 'desc')
            ->get()
            ->unique('id')
            ->take(3);

        if ($relatedNews->count() < 3) {
            $relatedNews = $relatedNews->merge(Article::where('article.id', '!=', $this->id)
                ->join('content_item', 'article.id', '=', 'content_item.id')
                ->where('content_item.is_deleted', false)
                ->where('topic_id', $this->topic_id)
                ->orderBy('content_item.academy_score', 'desc')
                ->get())
                ->unique('id')
                ->take(3 - $relatedNews->count());
        }

        return $relatedNews;
    }

    public function sameAuthorNews()
    {
        return Article::where('article.id', '!=', $this->id)
            ->join('content_item', 'article.id', '=', 'content_item.id')
            ->where('content_item.is_deleted', false)
            ->where('content_item.author_id', $this->contentItem->author_id)
            ->orderBy('content_item.academy_score', 'desc')
            ->take(3)
            ->get();
    }
}
