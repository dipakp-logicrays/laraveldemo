<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentVote;
use Illuminate\Http\Request;

class CommentVoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Vote on a comment.
     */
    public function vote(Request $request, Comment $comment)
    {
        $request->validate([
            'type' => 'required|in:like,dislike'
        ]);

        $userId = auth()->id();
        $voteType = $request->type;

        // Check if user has already voted
        $existingVote = CommentVote::where('comment_id', $comment->id)
                                   ->where('user_id', $userId)
                                   ->first();

        if ($existingVote) {
            if ($existingVote->type === $voteType) {
                // User is clicking the same vote type, so remove the vote
                $existingVote->delete();
                $action = 'removed';
            } else {
                // User is changing their vote
                $existingVote->update(['type' => $voteType]);
                $action = 'changed';
            }
        } else {
            // Create new vote
            CommentVote::create([
                'comment_id' => $comment->id,
                'user_id' => $userId,
                'type' => $voteType
            ]);
            $action = 'added';
        }

        // Get updated counts
        $likesCount = $comment->fresh()->likes()->count();
        $dislikesCount = $comment->fresh()->dislikes()->count();

        // Return JSON response for AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'action' => $action,
                'likes_count' => $likesCount,
                'dislikes_count' => $dislikesCount,
                'user_vote' => $action === 'removed' ? null : $voteType
            ]);
        }

        // For non-AJAX requests
        return back()->with('success', 'Vote recorded successfully!');
    }

    /**
     * Remove vote from a comment.
     */
    public function removeVote(Request $request, Comment $comment)
    {
        $vote = CommentVote::where('comment_id', $comment->id)
                           ->where('user_id', auth()->id())
                           ->first();

        if ($vote) {
            $vote->delete();
        }

        // Get updated counts
        $likesCount = $comment->fresh()->likes()->count();
        $dislikesCount = $comment->fresh()->dislikes()->count();

        // Return JSON response for AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'likes_count' => $likesCount,
                'dislikes_count' => $dislikesCount,
                'user_vote' => null
            ]);
        }

        return back()->with('success', 'Vote removed successfully!');
    }
}
