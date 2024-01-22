<?php

namespace App\Http\Controllers;

use App\Models\Appeal;

use Illuminate\Http\Request;

class AppealController extends Controller
{
    function create(Request $request)
    {
        $this->authorize('create', Appeal::class);

        $request->validate([
            'appeal_body' => 'required|string|max:255',
        ]);

        $appeal = Appeal::where('submitter_id', auth()->user()->id)->first();

        if ($appeal !== null) {
            return redirect()->back()->withErrors(['other_appeal' => 'You have already submitted an appeal.']);
        }

        Appeal::create([
            'body' => $request->appeal_body,
            'submitter_id' => auth()->user()->id,
        ]);

        return redirect()->back()->with('success', 'Appeal submitted successfully.');
    }

    function handle(Int $appealId, Request $request)
    {
        $this->authorize('handle', Appeal::class);

        $appeal = Appeal::find($appealId);

        if ($appeal === null) {
            return redirect()->back()->withErrors(['appeal_not_found' => 'Appeal not found.']);
        }

        if($request->handle === 'accept') {
            $appeal->submitter->is_blocked = false;
            $appeal->submitter->save();
            $appeal->delete();
            return redirect()->back()->with('success', 'Appeal accepted successfully.');
        }

        else if ($request->handle === 'reject') {
            $appeal->delete();
            return redirect()->back()->with('success', 'Appeal rejected successfully.');
        }

        else {
            return redirect()->back()->withErrors(['invalid_handle' => 'Invalid handle.']);
        }
    }

    function showAll(Request $request)
    {
        $this->authorize('getAppeals', Appeal::class);

        $request->validate([
            'page' => 'integer|min:1',
        ]);

        $page = $request->has('page') ? $request->input('page') : 1;
        $items_per_page = 10;

        $appeals = Appeal::orderBy('date_time', 'desc')
            ->paginate($items_per_page, ['*'], 'page', $page);

        $isLastPage = $appeals->currentPage() >= $appeals->lastPage();
        $count = $appeals->total();

        $view = view('partials.administration.appeals-list', ['appeals' => $appeals])->render();

        return response()->json([
            'html' => $view,
            'isLastPage' => $isLastPage,
            'count' => $count,
        ]);
    }
}
