<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Notification;
use App\Models\ContentNotification;
use App\Models\UndefinedTopicNotification;
use App\Events\NotificationEvent;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function create()
    {
        //
    }


    public function showAll(Request $request)
    {
        $this->authorize('viewAll', Topic::class);
        
        $page = $request->has('page') ? $request->input('page') : 1;
        $items_per_page = 10;

        $topics = Topic::paginate($items_per_page, ['*'], 'page', $page);
        $isLastPage = $topics->currentPage() >= $topics->lastPage();

        $view = view('partials.administration.all-topics', [
            'topics' => $topics
        ])->render();

        return response()->json([
            'html' => $view,
            'isLastPage' => $isLastPage,
        ]);
    }

    public function update(int $id, Request $request) {

        $topic = Topic::findOrFail($id);
        $this->authorize('update', $topic);

        if ($request->name === null) {
            return redirect()->back()
            ->withInput()
            ->withErrors(['snackbar' => 'Topic name cannot be empty']);
        }

        if (Topic::where('name', '=', $request->name)->first() !== null) {
            return redirect()->back()
            ->withInput()
            ->withErrors(['snackbar' => 'Topic name already taken']);
        }

        if (!preg_match('/^[a-zA-Z\s]+$/', $request->name)) {
            return redirect()->back()
            ->withInput()
            ->withErrors(['snackbar' => 'Topic name can only contain letters and spaces']);
        }

        $topic->update([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'Topic updated successfully');
    }

    public function delete(Int $id)
    {
        $topic = Topic::findOrFail($id);

        $this->authorize('delete', $topic);

        foreach ($topic->articles as $article) {
            TopicController::createUndefinedTopicNotification($article);
        }

        $topic->delete();

        return redirect()->back()->with('success', 'Topic deleted successfully');
    }

    static function createUndefinedTopicNotification($item)
    {
        event(new NotificationEvent("/articles/$item->id", $item->author()->id, "Your article as been markeed as undefined topic!"));
    }
}
