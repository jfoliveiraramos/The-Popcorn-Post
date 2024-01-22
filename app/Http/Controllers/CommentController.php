<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Article;
use App\Models\ContentItem;
use App\Models\Notification;
use App\Models\ContentNotification;
use App\Models\RemovalNotification;
use App\Models\EditNotification;
use Illuminate\Http\Request;
use App\Events\NotificationEvent;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
{
    public function create(Request $request, int $article_id)
    {
        $this->authorize('create', Comment::class);

        $request->validate([
            'body' => 'required|string',
            'reply_id' => 'nullable|integer',
        ]);

        $article = Article::find($article_id);
        if ($article == null) {
            return redirect()->back()->withInput()->withErrors(['article' => 'Article not found.']);
        }

        $contentItem = ContentItem::create([
            'body' => $request->body,
            'author_id' => Auth::user()->id,
        ]);

        $comment = Comment::create([
            'id' => $contentItem->id,
            'article_id' => $article_id,
            'reply_id' => $request->reply_id,
            'is_reply' => $request->reply_id != null,
        ]);

        if ($article->author()->id != Auth::user()->id) {
            $path = "/articles/$article_id";
            $message = "You have a new comment on your article!";
            event(new NotificationEvent($path, $article->author()->id, $message));
        }

        return redirect()->route('articles', ['id' => $article->id])->withSuccess('Comment created successfully!');
    }

    public function delete(Request $request, int $article_id, int $id)
    {
        $comment = Comment::find($id);
        if ($comment == null || $comment->contentItem->is_deleted) {
            return redirect()->back()->withInput()->withErrors(['comment' => 'Comment not found.']);
        }

        $this->authorize('delete', $comment);

        $replies = $comment->replies;
        foreach ($replies as $reply) {
            if ($reply->author()->id != Auth::user()->id) {
                CommentController::createRemovalNotification($reply);
            }

            $reply->contentItem->update([
                'is_deleted' => true,
            ]);
        }
        if ($comment->author()->id != Auth::user()->id) {
            CommentController::createRemovalNotification($comment);
        }

        $comment->contentItem->update([
            'is_deleted' => true,
        ]);

        return redirect()->back()->withSuccess('Comment deleted successfully!');
    }

    public function update(Request $request, int $article_id, int $id)
    {
        $request->validate([
            'body' => 'required|string',
        ]);
        $comment = Comment::findOrfail($id);
        if ($comment == null || $comment->contentItem->is_deleted) {
            return redirect()->back()->withInput()->withErrors(['comment' => 'Comment not found.']);
        }

        $this->authorize('update', $comment);

        if ($request->body == $comment->contentItem->body) {
            return redirect()->back()->withSuccess('Comment updated successfully!');
        }
        
        if ($comment->author()->id != Auth::user()->id) {
            CommentController::createEditNotification($comment);
        }

        $comment->contentItem->update([
            'body' => $request->body,
        ]);

        return redirect()->back()->withSuccess('Comment deleted successfully!');
    }

    static function createRemovalNotification($comment)
    {
        $notification = Notification::create([
            'notified_id' => $comment->author()->id,
        ]);

        $contentNotification = ContentNotification::create([
            'id' => $notification->id,
            'content_item_id' => $comment->id,
        ]);

        $removalNotification = RemovalNotification::create([
            'id' => $contentNotification->id,
        ]);

        event(new NotificationEvent(NULL, $comment->author()->id, "One of your contributions was deleted!"));
    }

    static function createEditNotification($comment)
    {
        $notification = Notification::create([
            'notified_id' => $comment->author()->id,
        ]);

        $contentNotification = ContentNotification::create([
            'id' => $notification->id,
            'content_item_id' => $comment->id,
        ]);

        $editNotification = EditNotification::create([
            'id' => $contentNotification->id,
        ]);

        event(new NotificationEvent("/articles/$comment->article_id", $comment->author()->id, "One of your contributions was edited!"));
    }
}
