<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'category_id',
        'user_id',
        'status',
        'published_at',
        'views'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views' => 'integer',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // Accessors & Mutators
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function getExcerptAttribute($value)
    {
        return $value ?: Str::limit($this->content, 150);
    }

    public function getReadingTimeAttribute()
    {
        $words = str_word_count(strip_tags($this->content));
        $minutes = ceil($words / 200); // Average reading speed

        return $minutes . ' min read';
    }

    // Helper Methods
    public function incrementViews()
    {
        $this->increment('views');
    }

    public function isPublished()
    {
        return $this->status === 'published' &&
                $this->published_at &&
                $this->published_at->lte(now());
    }

    /**
     * Get the comments for the post.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)
                    ->approved()
                    ->root()
                    ->latest();
    }

    /**
     * Get all comments including unapproved (for admin).
     */
    public function allComments()
    {
        return $this->hasMany(Comment::class)->root()->latest();
    }

    /**
     * Get the comment count.
     */
    public function getCommentCountAttribute()
    {
        return $this->comments()->count();
    }
}
