<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Member;
use App\Models\ContentItem;
use App\Models\Tag;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Displays the homepage.
     */
    public function show()
    {
        $trending_tags = Tag::trending(5);
        return view('pages.home', [
            'trending_tags' => $trending_tags,
            'title' => 'Home - The Popcorn Post'
        ]);
    }

    function getFeed(Request $request)
    {
        $type = $request->input('type');
        $content_type = $request->input('contentType');
        $page = $request->has('page') ? $request->input('page') : 1;
        $items_per_page = 4;

        switch ($type) {
            case 'top':
                $attribute = 'academy_score';
                break;
                // case 'trending':
                //     $attribute = TBD;
                //     break;
            case 'new':
                $attribute = 'date_time';
                break;
            default:
                abort(400);
        }

        switch ($content_type) {
            case 'articles_comments':
                if (!Gate::authorize('getFeedComments'))
                    abort(403);
                $items_aux = ContentItem::where('is_deleted', false)->orderBy($attribute, 'desc')->paginate($items_per_page, ['*'], 'page', $page);
                $isLastPage = $items_aux->currentPage() >= $items_aux->lastPage();
                $items = array_map(function ($item) {
                    $aux = Article::find($item->id);
                    if ($aux) {
                        return $aux;
                    }
                    return Comment::find($item->id);
                }, $items_aux->items());
                break;
            case 'articles':
                $items = Article::join('content_item', 'article.id', '=', 'content_item.id')
                ->where('is_deleted', false)->orderBy($attribute, 'desc')->paginate($items_per_page, ['*'], 'page', $page);
                $isLastPage = $items->currentPage() >= $items->lastPage();
                break;
            case 'comments':
                if (!Gate::authorize('getFeedComments'))
                abort(403);
                $items = Comment::join('content_item', 'comment.id', '=', 'content_item.id')
                ->where('is_deleted', false)->orderBy($attribute, 'desc')->paginate($items_per_page, ['*'], 'page', $page);
                $isLastPage = $items->currentPage() >= $items->lastPage();
                break;
            default:
                abort(400);
        }
        $view = view('partials.feed.feed', [
            'items' => $items
        ])->render();

        return response()->json([
            'html' => $view,
            'isLastPage' => $isLastPage
        ]);
    }

    function getMemberFeed(Request $request, string $username)
    {
        if (!Auth::check())
        {
            abort(401);
        }
        if (Auth::user()->username != $username)
        {
            abort(403);
        }
        $content_type = $request->input('contentType');
        $page = $request->has('page') ? $request->input('page') : 1;
        $member = Member::where('username', $username)->first();
        $items_per_page = 4;

        switch ($content_type) {
            case 'articles_comments':
                $items_aux = DB::table('content_item')
                ->leftJoin('article', 'content_item.id', '=', 'article.id')
                ->leftJoin('tag_article', 'article.id', '=', 'tag_article.article_id')
                ->leftJoin('comment', 'content_item.id', '=', 'comment.id')
                ->where('content_item.is_deleted', false)
                ->where(function ($query) use ($member) {
                    $query->whereIn('author_id', $member->following->pluck('id')->toArray())
                        ->orWhereIn('tag_article.tag_id', $member->tags->pluck('id')->toArray());
                })
                ->select('content_item.*')
                ->distinct()
                ->orderBy('date_time', 'desc')
                ->paginate($items_per_page, ['*'], 'page', $page);
                $isLastPage = $items_aux->currentPage() >= $items_aux->lastPage();
                $items = array_map(function ($item) {
                    $aux = Article::find($item->id);
                    if ($aux) {
                        return $aux;
                    }
                    return Comment::find($item->id);
                }, $items_aux->items());
                break;
            case 'articles':
                $items = Article::join('content_item', 'article.id', '=', 'content_item.id')
                ->where('is_deleted', false)
                ->where(function ($query) use ($member) {
                    $query->whereIn('author_id', $member->following->pluck('id')->toArray())
                        ->orWhere(function ($query) use ($member) {
                            $query->whereHas('tags', function ($tagQuery) use ($member) {
                                $tagQuery->whereIn('id', $member->tags->pluck('id'));
                            });
                        });
                })
                ->distinct()
                ->orderBy('date_time', 'desc')
                ->paginate($items_per_page, ['*'], 'page', $page);

                $isLastPage = $items->currentPage() >= $items->lastPage();
                break;
            case 'comments':
                $items = Comment::join('content_item', 'comment.id', '=', 'content_item.id')
                    ->whereIn('author_id', $member->following->pluck('id')->toArray()
                    )
                    ->where('is_deleted', false)
                    ->orderBy('date_time', 'desc')->paginate($items_per_page, ['*'], 'page', $page);
                $isLastPage = $items->currentPage() >= $items->lastPage();
                break;
            default:
                abort(400);
        }
        $view = view('partials.feed.feed', [
            'items' => $items
        ])->render();

        return response()->json([
            'html' => $view,
            'isLastPage' => $isLastPage
        ]);
    }
}
