<?php

namespace App\Http\Controllers;

use App\Models\ContentItem;
use App\Models\Notification;
use App\Models\ContentNotification;
use App\Models\UpvoteNotification;
use App\Models\Comment;
use App\Models\Article;
use App\Events\NotificationEvent;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailables\Content;

class UpvoteNotificationController extends Controller
{
    
    public function create(Request $request)
    {
        $request->validate([
            'content_item_id' => 'required|integer',
            'notified_id' => 'required|integer',
        ]);

        $this->authorize('create', [UpvoteNotification::class, $request->notified_id]);

        $academy_score = ContentItem::find($request->content_item_id)->academy_score;
        if ($academy_score <= 0) return;

        if (!in_array($academy_score, UpvoteNotification::$celebrated_amounts)) return;
        $contentNotifications = ContentNotification::where('content_item_id', $request->content_item_id)->get();
        if ($contentNotifications != null) {
            foreach ($contentNotifications as $contentNotification) {
                $upvoteNotification = UpvoteNotification::find($contentNotification->id);
                if ($upvoteNotification != null && $upvoteNotification->amount == $academy_score) {
                    return;
                }
            }
        }
        $notification = Notification::create([
            'notified_id' => $request->notified_id,
        ]);

        $contentNotification = ContentNotification::create([
            'id' => $notification->id,
            'content_item_id' => $request->content_item_id,
        ]);

        $upvoteNotification = UpvoteNotification::create([
            'id' => $contentNotification->id,
            'amount' => $academy_score,
        ]);

        if (Article::find($request->content_item_id) != null) {
            $path = "/articles/" . $request->content_item_id;
            if ($academy_score == 1) {
                $message = "Your article has reached 1 upvote!";
            } else {
                $message = "Your article has reached " . $academy_score . " upvotes!";
            }
            event(new NotificationEvent($path, $request->notified_id, $message));
        } else {
            $comment = Comment::find($request->content_item_id);
            $path = "/articles/" . $comment->article->id;
            if ($academy_score == 1) {
                $message = "Your comment has reached 1 upvote!";
            } else {
                $message = "Your comment has reached " . $academy_score . " upvotes!";
            }
            event(new NotificationEvent($path, $request->notified_id, $message));
        }
    }
}
