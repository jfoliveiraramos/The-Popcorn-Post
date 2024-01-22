<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Article;
use Illuminate\Http\Request;

class TagController extends Controller
{

    public function show(String $name)
    {
        $tag = Tag::where('name', '=', $name)->firstOrFail();
        $this->authorize('view', $tag);
        $trending_tags = Tag::trending(5);

        return view('pages.tag', [
            'tag' => $tag,
            'trending_tags' => $trending_tags,
            'title' => '#' . $tag->name . ' - The Popcorn Post'
        ]);
    }

    public function showAll(Request $request)
    {
        $this->authorize('viewAll', Tag::class);

        $page = $request->has('page') ? $request->input('page') : 1;
        $items_per_page = 10;

        $tags = [];

        if ($request->tagsType === 'banned') {
            $tags = Tag::getBannedTags()->paginate($items_per_page, ['*'], 'page', $page);
        } else if ($request->tagsType === 'unbanned') {
            $tags = Tag::getUnbannedTags()->paginate($items_per_page, ['*'], 'page', $page);
        } else if ($request->tagsType === 'all') {
            $tags = Tag::paginate($items_per_page, ['*'], 'page', $page);
        }

        $isLastPage = $tags->currentPage() === $tags->lastPage();

        $view = view('partials.administration.all-tags', [
            'tags' => $tags
        ])->render();

        return response()->json([
            'html' => $view,
            'isLastPage' => $isLastPage,
        ]);
    }

    public function follow(String $name)
    {
        $tag = Tag::where('name', '=', $name)->firstOrFail();
        $this->authorize('follow', $tag);

        $tag->followers()->attach(auth()->user()->id);
        return redirect()->back()->withSuccess('You are now following #' . $tag->name);
    }

    public function unfollow(String $name)
    {
        $tag = Tag::where('name', '=', $name)->firstOrFail();
        $this->authorize('unfollow', $tag);

        $tag->followers()->detach(auth()->user()->id);
        return redirect()->back()->withSuccess('You are no longer following #' . $tag->name);
    }

    public function update(Request $request, String $name)
    {
        $tag = Tag::where('name', '=', $name)->firstOrFail();
        $this->authorize('update', Tag::class);

        if ($request->name != $tag->name) {
            
            if (!$request->name) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['snackbar' => 'Tag name cannot be empty']);
            }

            if (Tag::where('name', '=', $request->name)->exists()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['snackbar' => 'Tag already exists']);
            }

            if (preg_match('/\s|:|;/', $request->name)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['snackbar' => 'Tag name cannot contain spaces, colons, or semi-colons']);
            }

            $tag->name = $request->name;
        }

        if ($request->is_banned) {
            $tag->is_banned = true;
        } else {
            $tag->is_banned = false;
        }

        $tag->save();

        if (!$request->is_banned && $request->in_tag_page) {
            return redirect()->route('tags', ['name' => $tag->name])->withSuccess('Tag updated successfully!');
        }

        return redirect()->route('administration')->withSuccess('Tag updated successfully!');
    }

    public function getArticles(Request $request, String $name)
    {
        $tag = Tag::where('name', '=', $name)->firstOrFail();
        $this->authorize('view', $tag);
        
        $page = $request->has('page') ? $request->input('page') : 1;
        $items_per_page = 3;

        $articles = Article::join('content_item', 'article.id', '=', 'content_item.id')
            ->where('is_deleted', false)
            ->join('tag_article', 'article.id', '=', 'tag_article.article_id')
            ->where('tag_article.tag_id', $tag->id)
            ->orderBy('academy_score', 'desc')
            ->paginate($items_per_page, ['*'], 'page', $page);

        $isLastPage = $articles->currentPage() >= $articles->lastPage();

        $html = view('partials.tag.articles', ['articles' => $articles , 'tag' => $tag])->render();
        return response()->json([
            'html' => $html,
            'isLastPage' => $isLastPage
        ]);
    }
}
