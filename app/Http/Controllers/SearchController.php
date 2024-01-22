<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Tag;
use App\Models\Topic;
use App\Models\Member;
use App\Models\Article;
use App\Models\Comment;
use App\Models\ContentItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    function get_time($time) {

        switch ($time) {
            case 'all':
                return null;
            case 'past_hour':
                return new DateTime('-1 hour');
            case 'past_day':
                return new DateTime('-1 day');
            case 'past_week':
                return new DateTime('-1 week');
            case 'past_month':
                return new DateTime('-1 month');
            case 'past_year':
                return new DateTime('-1 year');
            default:
                return null;
        }
    }

    function search(Request $request)
    {
        
        $query = $request->input('query', null);
        $type = $request->input('type', 'articles_comments');
        $exactMatch = $request->input('exactMatch', false);
        $time = $request->input('time', 'all');
        $minScore = $request->input('minScore', null);
        $maxScore = $request->input('maxScore', null);
        $topic = $request->input('topic', 'all');
        $sort = $request->input('sort', 'highest_score');

        if ($type == 'members' && !Auth::check()) {
            return redirect()->back()->withErrors(['type' => 'You must be logged in to search for members.']);
        }

        $exactMatch = $exactMatch === 'true' ? true : false;
        $date_time = $this->get_time($time);
        $minScore = $minScore == null ? null : (intval($minScore) < -1000000 ? -1000000 : intval($minScore));
        $maxScore = $maxScore == null ? null : (intval($maxScore) > 1000000 ? 1000000 : intval($maxScore));

        switch ($type) {
            case 'articles_comments': {
                    $ogItems = ContentItem::search($query, $exactMatch, $date_time, $minScore, $maxScore, $topic, $sort);
                    $articles = Article::whereIn('id', $ogItems->pluck('id'))->get();
                    $comments = Comment::whereIn('id', $ogItems->pluck('id'))->get();
                    $items = $articles->merge($comments);
                    $items = $items->sortBy(function ($item) use ($ogItems) {
                        return array_search($item->id, $ogItems->pluck('id')->toArray());
                    });
                    break;
                }
            case 'articles':
                $items = Article::search($query, $exactMatch, $date_time, $minScore, $maxScore, $topic, $sort);
                break;
            case 'comments':
                $items = Comment::search($query, $exactMatch, $date_time, $minScore, $maxScore, $topic, $sort);
                break;
            case 'members':
                if (!Auth::check()) {
                    abort(401);
                }
                $items = Member::search($query, $exactMatch, $sort);
                break;
            case 'tags':
                $items = Tag::search($query, $exactMatch, $sort);
                break;
            default:
                return redirect()->back()->withErrors(['type' => 'Invalid type.']);
        }

        
        return view('pages.search', [
            'query' => $query,
            'type' => $type,
            'exactMatch' => $exactMatch,
            'time' => $time,
            'minScore' => $minScore,
            'maxScore' => $maxScore,
            'selectedTopic' => $topic,
            'sort' => $sort,
            'items' => $items,
            'topics' => Topic::where('id', '!=', 0)->get(),
            'trending_tags' => Tag::trending(5),
            'title' => 'Search - The Popcorn Post'
        ]);
    }

    function searchUpdate(Request $request)
    {

        $query = $request->input('query', null);
        $type = $request->input('type', 'articles_comments');
        $exactMatch = $request->input('exactMatch', false);
        $time = $request->input('time', 'all');
        $minScore = $request->input('minScore', null);
        $maxScore = $request->input('maxScore', null);
        $topic = $request->input('topic', null);
        $sort = $request->input('sort', 'highest_score');
        $page = $request->input('page', 1);

        $exactMatch = $exactMatch === 'true' ? true : false;
        $date_time = $this->get_time($time);
        $minScore = $minScore == null ? null : (intval($minScore) < -1000000 ? -1000000 : intval($minScore));
        $maxScore = $maxScore == null ? null : (intval($maxScore) > 1000000 ? 1000000 : intval($maxScore));

        switch ($type) {
            case 'articles_comments': {
                    $ogItems = ContentItem::search($query, $exactMatch, $date_time, $minScore, $maxScore, $topic, $sort, $page);
                    $isLastPage = $ogItems->currentPage() == $ogItems->lastPage();
                    $articles = Article::whereIn('id', $ogItems->pluck('id'))->get();
                    $comments = Comment::whereIn('id', $ogItems->pluck('id'))->get();
                    $items = $articles->merge($comments);
                    $items = $items->sortBy(function ($item) use ($ogItems) {
                        return array_search($item->id, $ogItems->pluck('id')->toArray());
                    });
                    break;
                }
            case 'articles':
                $items = Article::search($query, $exactMatch, $date_time, $minScore, $maxScore, $topic, $sort, $page);
                $isLastPage = $items->currentPage() >= $items->lastPage();
                break;
            case 'comments':
                $items = Comment::search($query, $exactMatch, $date_time, $minScore, $maxScore, $topic, $sort, $page);
                $isLastPage = $items->currentPage() == $items->lastPage();
                break;
            case 'members':
                if (!Auth::check()) {
                    abort(401);
                }
                $items = Member::search($query, $exactMatch, $sort, $page);
                $isLastPage = $items->currentPage() >= $items->lastPage();
                break;
            case 'tags':
                $items = Tag::search($query, $exactMatch, $sort, $page);
                $isLastPage = $items->currentPage() >= $items->lastPage();
                break;
            default:
                return redirect()->back()->withErrors(['type' => 'Invalid type.']);
        }

        $view = view('partials.search.search-results', [
            'items' => $items
        ])->render();

        return response()->json([
            'html' => $view,
            'isLastPage' => $isLastPage
        ]);
    }
}
