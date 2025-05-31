<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created comment.
     */
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string|min:3|max:1000',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        // Check if parent comment belongs to the same post
        if ($request->parent_id) {
            $parentComment = Comment::find($request->parent_id);
            if ($parentComment->post_id !== $post->id) {
                return back()->with('error', 'Invalid parent comment.');
            }
        }

        $comment = $post->comments()->create([
            'user_id' => auth()->id(),
            'parent_id' => $request->parent_id,
            'content' => $validated['content'],
            'is_approved' => true, // Set to false if you want moderation
        ]);

        return back()->with('success', 'Comment posted successfully!');
    }

    /**
     * Update the specified comment.
     */
    public function update(Request $request, Comment $comment)
    {
        // Check if user can update this comment
        if ($comment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'content' => 'required|string|min:3|max:1000'
        ]);

        $comment->update([
            'content' => $validated['content']
        ]);

        return back()->with('success', 'Comment updated successfully!');
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(Comment $comment)
    {
        // Check if user can delete this comment
        if ($comment->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        // Check if comment has replies
        if ($comment->hasReplies()) {
            return back()->with('error', 'Cannot delete comment with replies.');
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted successfully!');
    }

    /**
     * Toggle comment approval (admin only).
     */
    public function toggleApproval(Comment $comment)
    {
        // Check if user is admin
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $comment->update([
            'is_approved' => !$comment->is_approved
        ]);

        $status = $comment->is_approved ? 'approved' : 'unapproved';
        return back()->with('success', "Comment {$status} successfully!");
    }
}
