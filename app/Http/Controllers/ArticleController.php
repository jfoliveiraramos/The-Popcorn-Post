<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Topic;
use App\Models\Article;
use App\Models\Comment;
use App\Models\ContentItem;
use App\Models\ArticleImage;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\EditNotification;
use App\Events\NotificationEvent;
use App\Models\ContentNotification;
use App\Models\RemovalNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ArticleController extends Controller
{
    public function create(Request $request)
    {

        $this->authorize('create', Article::class);

        $request->validate([
            'title' => 'required|string|max:100',
            'body' => 'required|string',
            'topic' => 'required|integer',
        ]);

        $tags = preg_split('/[,; ]+/', $request->tags, -1, PREG_SPLIT_NO_EMPTY);

        if (count($tags) > 6) {
            return redirect()->back()->withInput()->withErrors(['tags' => 'You can only have up to 6 tags.']);
        }

        foreach ($tags as $tagName) {

            $tag = Tag::where('name', $tagName)->first();

            if ($tag != null && $tag->is_banned) {
                return redirect()->back()->withInput()->withErrors(['tags' => 'One or more of the tags you entered are banned.']);
            }
        }

        $contentItem = ContentItem::create([
            'body' => $request->body,
            'author_id' => Auth::user()->id
        ]);

        $article = Article::create([
            'id' => $contentItem->id,
            'title' => $request->title,
            'topic_id' => $request->topic
        ]);

        $tags = preg_split('/[,; ]+/', $request->tags, -1, PREG_SPLIT_NO_EMPTY);
        $tags = array_unique($tags);

        foreach ($tags as $tagName) {
            $tag = Tag::firstOrCreate([
                'name' => $tagName
            ]);
            $article->tags()->attach($tag->id);
        }

        $images = [];

        if ($request->csv) {

            foreach ($request->csv as $id) {

                $dir = public_path('images/tmp/' . $id);

                $file = File::allFiles($dir)[0];

                $image_name = $file->getFilename();

                $origin = $file->getPathname();

                $destination = public_path('images/articles/' . $image_name);

                rename($origin, $destination);
                rmdir($dir);

                $images[] = $image_name;
            }
        }

        foreach ($images as $image) {

            ArticleImage::create([
                'file_name' => $image,
                'article_id' => $article->id
            ]);
        }

        return redirect()->route('articles', ['id' => $article->id])->withSuccess('Article created successfully!');
    }

    public function show(string $id)
    {
        $article = Article::find($id);
        if ($article == null){
            $comment = Comment::findOrFail($id);
            $article = Article::findOrFail($comment->article_id);
            return redirect('/articles/' . $article->id)->withSuccess('Redirected to article!');
        }

        if ($article->contentItem->is_deleted) {
            return redirect()->route('home')->withErrors(['article' => 'Article not found.']);
        }
        
        return view('pages.article', [
            'article' => $article,
            'title' => $article->title . ' - The Popcorn Post'
        ]);
    }

    public function showCreateArticle()
    {
        $this->authorize('create', Article::class);

        $topics = Topic::all();

        return view('pages.create-article', [
            'topics' => $topics,
            'title' => 'Create Article - The Popcorn Post'
        ]);
    }

    public function edit(int $articleId)
    {
        $article = Article::findOrFail($articleId);
        $this->authorize('edit', $article);

        $articleTags = $article->tags();
        $tags = $articleTags->pluck('name')->implode(', ');

        return view('pages.edit-article', [
            'article' => $article,
            'topics' => Topic::all(),
            'tags' => $tags,
            'title' => 'Edit '. $article->title  . ' - The Popcorn Post'
        ]);
    }

    public function update(Request $request, int $articleId)
    {
        $article = Article::findOrFail($articleId);
        $this->authorize('update', $article);

        $request->validate([
            'title' => 'required|string|max:100',
            'body' => 'required|string',
            'topic' => 'required|integer',
            'files.*' => 'required|mimes:png,jpg,jpeg|max:2048',
        ]);
        $tags = preg_split('/[,; ]+/', $request->tags, -1, PREG_SPLIT_NO_EMPTY);
        $tags = array_unique($tags);

        if (count($tags) > 6) {
            return redirect()->back()->withInput()->withErrors(['tags' => 'You can only have up to 6 tags.']);
        }

        foreach ($tags as $tagName) {

            $tag = Tag::where('name', $tagName)->first();

            if ($tag != null && $tag->is_banned) {
                return redirect()->back()->withInput()->withErrors(['tags' => 'One or more of the tags you entered are banned.']);
            }
        }

        $images = [];

        if ($request->csv) {

            if (in_array(null, $request->csv)) {
                return redirect()->back()->withInput()->withErrors(['csv' => 'Wait for images to upload.']);
            }


            foreach ($request->csv as $id) {

                $destination = public_path('images/articles/' . $id);

                if (file_exists($destination)) {
                    $images[] = $id;
                    continue;
                }

                $dir = public_path('images/tmp/' . $id);

                $file = File::allFiles($dir)[0];

                $image_name = $file->getFilename();

                $origin = $file->getPathname();

                $destination = public_path('images/articles/' . $image_name);

                $images[] = $image_name;

                if (file_exists($destination)) {
                    continue;
                }

                rename($origin, $destination);

                rmdir($dir);
            }
        }

        foreach ($article->images as $image) {
            if (!in_array($image->file_name, $images)) {
                unlink(public_path('images/articles/' . $image->file_name));
                $image->delete();
            }
        }

        $article->images()->delete();

        foreach ($images as $image) {

            ArticleImage::create([
                'file_name' => $image,
                'article_id' => $article->id
            ]);
        }

        $article->title = $request->title;
        $article->contentItem->body = $request->body;
        $article->contentItem->save();
        $article->topic_id = $request->topic;
        $article->save();


        foreach ($tags as $tagName) {
            $tag = Tag::firstOrCreate([
                'name' => $tagName
            ]);

            if (!$article->tags()->where('tag_id', $tag->id)->exists()) {
                $article->tags()->attach($tag->id);
            }
        }

        foreach ($article->tags as $tag) {
            if (!in_array($tag->name, $tags)) {
                $article->tags()->detach($tag->id);
            }
        }

        if ($article->author()->id != Auth::user()->id) {
            ArticleController::createEditNotification($article);
        }

        return redirect()->route('articles', ['id' => $article->id])->withSuccess('Article updated successfully!');
    }

    public function delete(int $articleId)
    {
        $article = Article::findOrFail($articleId);
        $this->authorize('delete', $article);

        foreach ($article->comments as $comment) {
            if ($comment->author()->id != Auth::user()->id) {
                ArticleController::createRemovalNotification($comment);
            }

            $comment->contentItem->update([
                'is_deleted' => true
            ]);
        }

        if ($article->author()->id != Auth::user()->id) {
            ArticleController::createRemovalNotification($article);
        }

        $article->contentItem->update([
            'is_deleted' => true
        ]);

        return redirect()->route('home')->withSuccess('Article deleted successfully!');
    }
    static function createRemovalNotification($item)
    {
        $notification = Notification::create([
            'notified_id' => $item->author()->id,
        ]);

        $contentNotification = ContentNotification::create([
            'id' => $notification->id,
            'content_item_id' => $item->id,
        ]);

        $removalNotification = RemovalNotification::create([
            'id' => $contentNotification->id,
        ]);

        event(new NotificationEvent(null, $item->author()->id, "One of your contributions was deleted!"));
    }

    static function createEditNotification($item)
    {
        $notification = Notification::create([
            'notified_id' => $item->author()->id,
        ]);

        $contentNotification = ContentNotification::create([
            'id' => $notification->id,
            'content_item_id' => $item->id,
        ]);

        $editNotification = EditNotification::create([
            'id' => $contentNotification->id,
        ]);

        event(new NotificationEvent("/articles/$item->article_id", $item->author()->id, "One of your contributions was edited!"));
    }

    public function getComments(Request $request, string $id)
    {
        $article = Article::findOrFail($id);
        $this->authorize('getComments', $article);
        $request->validate([
            'sort' => 'required|string|in:asc-date,desc-date,asc-score,desc-score',
        ]);
        $page = $request->has('page') ? $request->input('page') : 1;
        $items_per_page = 5;
        if ($request->sort == 'asc-date') {
            $comments = $article->directComments()->leftJoin('content_item', 'comment.id', '=', 'content_item.id')->where('is_deleted', false)->orderBy('date_time', 'asc')->paginate($items_per_page, ['*'], 'page', $page);
        } else if ($request->sort == 'desc-date') {
            $comments = $article->directComments()->leftJoin('content_item', 'comment.id', '=', 'content_item.id')->where('is_deleted', false)->orderBy('date_time', 'desc')->paginate($items_per_page, ['*'], 'page', $page);
        } else if ($request->sort == 'asc-score') {
            $comments = $article->directComments()->leftJoin('content_item', 'comment.id', '=', 'content_item.id')->where('is_deleted', false)->orderBy('academy_score', 'asc')->paginate($items_per_page, ['*'], 'page', $page);
        } else if ($request->sort == 'desc-score') {
            $comments = $article->directComments()->leftJoin('content_item', 'comment.id', '=', 'content_item.id')->where('is_deleted', false)->orderBy('academy_score', 'desc')->paginate($items_per_page, ['*'], 'page', $page);
        };

        $isLastPage = $page >= $comments->lastPage();

        $view = view('partials.article.create-comments', [
            'comments' => $comments
        ])->render();

        return response()->json([
            'html' => $view,
            'isLastPage' => $isLastPage
        ]);
    }
}
