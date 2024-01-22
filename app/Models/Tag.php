<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    public $timestamps  = false;

    protected $table = 'tag';

    protected $fillable = [
        'name',
    ];

    protected $hidden = [
        'is_banned',
    ];

    public function articles() {
        return $this->belongsToMany(Article::class, 'tag_article', 'tag_id', 'article_id');
    }

    public function nonDeletedArticles() {
        return $this->belongsToMany(Article::class, 'tag_article', 'tag_id', 'article_id')
            ->join('content_item', 'article.id', '=', 'content_item.id')
            ->where('content_item.is_deleted', false);
    }

    public function followers() {
        return $this->belongsToMany(Member::class, 'follow_tag', 'tag_id', 'member_id');
    }

    public static function search($query, $exactMatch, $sort, $page = 1) {

        $perPage = 10;

        switch($sort) {
            case 'highest_score':
                $tags = Tag::select('tag.*')
                ->leftJoin('tag_article', 'tag.id', '=', 'tag_article.tag_id')
                ->leftJoin('article', 'tag_article.article_id', '=', 'article.id')
                ->leftJoin('content_item', 'article.id', '=', 'content_item.id')
                ->where('content_item.is_deleted', false)
                ->groupBy('tag.id')
                ->orderByRaw('SUM(content_item.academy_score) DESC');

                break;
            case 'lowest_score':
                $tags = Tag::select('tag.*')
                ->leftJoin('tag_article', 'tag.id', '=', 'tag_article.tag_id')
                ->leftJoin('article', 'tag_article.article_id', '=', 'article.id')
                ->leftJoin('content_item', 'article.id', '=', 'content_item.id')
                ->where('content_item.is_deleted', false)
                ->groupBy('tag.id')
                ->orderByRaw('SUM(content_item.academy_score) ASC');
                break;
        }

        $tags = $tags->where('is_banned', false);

        if (!$query) {
            return $tags->paginate($perPage, ['*'], 'page', $page);
        }

        else if ($exactMatch) {
            $tags = Tag::where('name', '=', $query);
        } else {
            $searchTerms = preg_split('/\s+/', $query, -1, PREG_SPLIT_NO_EMPTY);
            $tags = Tag::Where(function ($query) use ($searchTerms) {
                    foreach ($searchTerms as $term) {
                        $term = trim($term);
                        $term = strtolower($term);
                        $query->orWhere('name', 'LIKE', "%$term%");
                    }
                });
        }
        return $tags->paginate($perPage, ['*'], 'page', $page);
    }

    public static function trending($n) {
        $tags = Tag::select('tag.*')
            ->leftJoin('tag_article', 'tag.id', '=', 'tag_article.tag_id')
            ->leftJoin('article', 'tag_article.article_id', '=', 'article.id')
            ->leftJoin('content_item', 'article.id', '=', 'content_item.id')
            ->leftJoin('follow_tag', 'tag.id', '=', 'follow_tag.tag_id')
            ->where('content_item.date_time', '>=', now()->subDays(7))
            ->where('content_item.is_deleted', false)
            ->where('tag.is_banned', false)
            ->groupBy('tag.id')
            ->orderByRaw('SUM(content_item.academy_score) DESC')
            ->orderByRaw('COUNT(follow_tag.member_id) DESC')
            ->take($n)
            ->get();

        return $tags;
    }

    public function getArticlesAcademyScore() {
        $articles = Article::select('article.*')
            ->leftJoin('tag_article', 'article.id', '=', 'tag_article.article_id')
            ->leftJoin('tag', 'tag_article.tag_id', '=', 'tag.id')
            ->leftJoin('content_item', 'article.id', '=', 'content_item.id')
            ->where('tag.id', $this->id)
            ->where('content_item.is_deleted', false)
            ->get();

        $score = 0;

        foreach ($articles as $article) {
            $score += $article->score();
        }

        return $score;
    }

    public static function getBannedTags() {
        return self::where('is_banned', true);
    }

    public static function getUnbannedTags() {
        return self::where('is_banned', false);
    }
}
