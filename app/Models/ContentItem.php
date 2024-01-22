<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentItem extends Model
{
    public $timestamps  = false;

    protected $table = 'content_item';

    protected $fillable = [
        'body',
        'author_id',
        'is_deleted',
    ];

    public function author()
    {
        return $this->belongsTo(Member::class, 'author_id', 'id');
    }

    static public function searchQuery($query, $exactMatch, $time, $minScore, $maxScore, $topic, $sort)
    {
        $items = ContentItem::where('is_deleted', false);

        if ($query) {
            if ($exactMatch) {
                $queryTerm = $query;
                $items = $items->where('body', 'LIKE', "%$query%")
                    ->orWhereIn('id', function ($query) use ($queryTerm) {
                        $query->select('id')
                            ->from('article')
                            ->where('title', 'LIKE', "%$queryTerm%");
                    })->orderBy('date_time', 'DESC');
            } else {
                $terms = preg_split('/\s+/', $query, -1, PREG_SPLIT_NO_EMPTY);
                $searchTerms = implode(' | ', $terms);
                $items = $items->whereRaw("tsvectors @@ to_tsquery('english', ?)", [$searchTerms])
                    ->orderByRaw("ts_rank(tsvectors, to_tsquery('english', ?)) DESC", [$searchTerms]);
            }
        }
        if ($minScore !== null) {
            $items = $items->where('academy_score', '>=', $minScore);
        }
        if ($maxScore !== null) {
            $items = $items->where('academy_score', '<=', $maxScore);
        }
        if ($time) {
            $items = $items->where('date_time', '>=', $time);
        }
        if ($topic !== 'all') {
            $items = $items->whereIn('id', function ($query) use ($topic) {
                $query->select('id')
                    ->from('article')
                    ->where('topic_id', $topic);

                $query->union(function ($query) use ($topic) {
                    $query->select('id')
                        ->from('comment')
                        ->whereIn('article_id', function ($query) use ($topic) {
                            $query->select('id')
                                ->from('article')
                                ->where('topic_id', $topic);
                        });
                });
            });
        }

        return $items;
    }

    static public function search($query, $exactMatch, $time, $minScore, $maxScore, $topic, $sort, $page = 1)
    {

        $perPage = 10;

        $items = ContentItem::searchQuery($query, $exactMatch, $time, $minScore, $maxScore, $topic, $sort);

        switch ($sort) {
            case 'highest_score':
                $items = $items->orderBy('academy_score', 'DESC');
                break;
            case 'lowest_score':
                $items = $items->orderBy('academy_score', 'ASC');
                break;
            case 'newest':
                $items = $items->orderBy('date_time', 'DESC');
                break;
            case 'oldest':
                $items = $items->orderBy('date_time', 'ASC');
                break;
        }

        $items = $items->paginate($perPage, ['*'], 'page', $page);

        return $items;
    }

    public function votes()
    {
        return $this->belongsToMany(Member::class, 'vote', 'content_item_id', 'member_id')->withPivot('weight');
    }

    public function votes_count()
    {
        return $this->votes()->where('is_deleted', false)->count();
    }

    public function score()
    {
        return $this->academy_score;
    }
}
