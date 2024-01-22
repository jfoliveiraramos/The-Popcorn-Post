<?php

namespace App\Http\Controllers;

use App\Models\ContentItem;
use Illuminate\Http\Request;

class ContentItemController extends Controller
{
    
    public function vote(Request $request)
    {
        $contentItemId = intval($request->contentItemId);
        $weight = intval($request->weight);
        $memberId = intval($request->memberId);

        try {
            $contentItem = ContentItem::findOrFail($contentItemId);
        }  catch (\Exception $exception) {
            return response()->json([
                "success" => false,
                "message" => "Item not found",
                "exception" => $exception->getMessage(),
            ]);
        }

        $this->authorize('vote', $contentItem);

        $existingVote = $contentItem->votes()->where('member_id', $memberId)->first();

        if ($existingVote !== null) {
            if ($existingVote->pivot->weight == $weight) {
                $contentItem->votes()->detach($memberId);
                $message = "Vote removed!";
                $vote = "none";
            }
            else {
                $contentItem->votes()->updateExistingPivot($memberId, ['weight' => $weight]);
                $message = "Vote updated!";
                $vote = $weight === 1 ? "upvote" : "downvote";
            }
        } else {
            $contentItem->votes()->attach($memberId, ['weight' => $weight]);
            $message = "Vote successful!";
            $vote = $weight === 1 ? "upvote" : "downvote";
        }

        $contentItem->refresh();

        return response()->json([
            "success" => true,
            "message" => $message,
            "score" => $contentItem->score(),
            "vote" => $vote
        ]);
    }
}
