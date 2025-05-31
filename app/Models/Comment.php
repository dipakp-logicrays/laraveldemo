<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'content',
        'is_approved'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    protected $with = ['user', 'replies'];

    /**
     * Get the post that owns the comment.
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the user that owns the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment.
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Get the replies for the comment.
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')
                    ->where('is_approved', true)
                    ->latest();
    }

    /**
     * Get all replies including unapproved (for admin).
     */
    public function allReplies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->latest();
    }

    /**
     * Scope to get only approved comments.
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope to get only root comments (not replies).
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Check if comment has replies.
     */
    public function hasReplies()
    {
        return $this->replies->count() > 0;
    }

    /**
     * Get the comment's created date for humans.
     */
    public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
