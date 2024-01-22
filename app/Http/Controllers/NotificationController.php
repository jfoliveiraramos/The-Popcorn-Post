<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Member;
use App\Events\NotificationEvent;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getNotifications(Request $request, string $username)
    {
        $request->validate([
            'page' => 'integer|min:1',
        ]);
        $page = $request->has('page') ? $request->input('page') : 1;
        $items_per_page = 10;
        $this->authorize('getNotifications', [Notification::class, $username]);
        $member = Member::where('username', $username)->firstOrFail();

        $notifications = Notification::with(['blockNotification', 'commentNotification', 'followNotification', 'contentNotification.upvoteNotification', 'contentNotification.editNotification', 'contentNotification.removalNotification', 'contentNotification.undefinedTopicNotification'])
            ->where('notified_id', $member->id)
            ->where('was_read', false)
            ->orderBy('date_time', 'desc')
            ->paginate($items_per_page, ['*'], 'page', $page);
        $isLastPage = $notifications->currentPage() >= $notifications->lastPage();
        $count = $notifications->total();

        $view = view('partials.notifications-list', ['notifications' => $notifications])->render();
        return response()->json([
            'html' => $view,
            'isLastPage' => $isLastPage,
            'count' => $count,
        ]);
    }

    public function toggleRead(Request $request, string $id)
    {
        $notification = Notification::find($id);
        $this->authorize('toggleRead', [Notification::class, $notification]);
        $notification->was_read = !$notification->was_read;
        $notification->save();
        return response()->json([
            'wasRead' => $notification->was_read,
        ]);
    }

    public function markAllAsRead(Request $request, string $username)
    {
        $this->authorize('markAllAsRead', [Notification::class, $username]);
        $member = Member::where('username', $username)->firstOrFail();
        $notifications = Notification::where('notified_id', $member->id)
            ->where('was_read', false)
            ->get();
        foreach ($notifications as $notification) {
            $notification->was_read = true;
            $notification->save();
        }
        return response()->json([
            'success' => true,
        ]);
    }
}
