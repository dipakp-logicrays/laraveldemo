<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Notifications\CommentReplyNotification;
use App\Notifications\NewCommentNotification;

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

        // Send notifications
        $this->sendNotifications($comment, $parentComment ?? null);

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

    /**
     * Send appropriate notifications
     */
    protected function sendNotifications(Comment $comment, ?Comment $parentComment)
    {
        // If this is a reply to another comment
        if ($parentComment) {
            // Don't notify if replying to own comment
            // if ($parentComment->user_id !== $comment->user_id) {
                $parentComment->user->notify(new CommentReplyNotification($comment, $parentComment));
            // }
        } else {
            // This is a root comment on a post
            // Notify post author if it's not their own comment
            // if ($comment->post->user_id !== $comment->user_id) {
                $comment->post->user->notify(new NewCommentNotification($comment));
            // }
        }

        // Optional: Notify other participants in the thread
        $this->notifyThreadParticipants($comment);
    }

    /**
     * Notify other participants in the comment thread
     */
    protected function notifyThreadParticipants(Comment $comment)
    {
        // Get all unique users who have commented on this post
        $participants = Comment::where('post_id', $comment->post_id)
            ->where('user_id', '!=', $comment->user_id) // Exclude the commenter
            ->where('user_id', '!=', $comment->post->user_id) // Exclude post author (already notified)
            ->select('user_id')
            ->distinct()
            ->with('user')
            ->get();

        // You can implement thread notifications here if desired
        // foreach ($participants as $participant) {
        //     $participant->user->notify(new ThreadActivityNotification($comment));
        // }
    }
}
