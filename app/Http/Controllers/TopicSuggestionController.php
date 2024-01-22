<?php

namespace App\Http\Controllers;

use App\Models\TopicSuggestion;
use App\Models\Topic;
use Illuminate\Http\Request;

class TopicSuggestionController extends Controller
{

    public function create(Request $request)
    {
        $this->authorize('create', TopicSuggestion::class);
        
        $request->validate([
            'topic_name' => 'required|string|regex:/^[A-Z]/|max:255',
        ]);

        $topicSuggestions = TopicSuggestion::where('name', $request->topic_name)->get();
        $topic = Topic::where('name', $request->topic_name)->get();

        if ($topicSuggestions->count() > 0 ) {
            return redirect()->back()->withErrors(['suggestion_exists' => 'This topic suggestion already exists.']);
        }

        if ($topic->count() > 0 ) {
            return redirect()->back()->withErrors(['topic_exists' => 'This topic already exists.']);
        }

        TopicSuggestion::create([
            'name' => $request->topic_name,
            'status' => 'Pending',
            'suggester_id' => auth()->user()->id,
        ]);

        return redirect()->back()->with('success', 'Topic suggestion submitted successfully.');
    }

    public function showAll(Request $request)
    {
        $this->authorize('viewAll', TopicSuggestion::class);
        
        $page = $request->has('page') ? $request->input('page') : 1;
        $items_per_page = 10;
        
        $suggestions = [];

        if ($request->suggestionsType === 'pending') {
            $suggestions = TopicSuggestion::getPendingSuggestions()->paginate($items_per_page, ['*'], 'page', $page);
        } else if ($request->suggestionsType === 'accepted') {
            $suggestions = TopicSuggestion::getAcceptedSuggestions()->paginate($items_per_page, ['*'], 'page', $page);
        } else if ($request->suggestionsType === 'rejected') {
            $suggestions = TopicSuggestion::getRejectedSuggestions()->paginate($items_per_page, ['*'], 'page', $page);
        } else if ($request->suggestionsType === 'all') {
            $suggestions = TopicSuggestion::paginate($items_per_page, ['*'], 'page', $page);
        }

        $isLastPage = $suggestions->currentPage() >= $suggestions->lastPage();

        $view = view('partials.administration.suggestions', [
            'suggestions' => $suggestions
        ])->render();

        return response()->json([
            'html' => $view,
            'isLastPage' => $isLastPage,
        ]);
    }

    public function update(Request $request, Int $id) {
        $this->authorize('update', TopicSuggestion::class);

        $request->validate([
            'status' => 'required|string|in:Accepted,Rejected',
        ]);

        $topicSuggestion = TopicSuggestion::findOrFail($id);

        $topicSuggestion->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Topic suggestion updated successfully.');
    }
}
