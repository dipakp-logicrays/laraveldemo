<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment_id',
        'user_id',
        'type'
    ];

    /**
     * Get the comment that owns the vote.
     */
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    /**
     * Get the user that owns the vote.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
