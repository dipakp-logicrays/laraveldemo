<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display posts for a specific tag
     */
    // public function show(Tag $tag)
    // {
    //     $posts = $tag->posts()
    //                  ->published()
    //                  ->latest('published_at')
    //                  ->paginate(10);

    //     return view('tags.show', compact('tag', 'posts'));
    // }

    /**
     * Display a listing of all tags
     */
    public function index()
    {
        $tags = Tag::withCount(['posts' => function ($query) {
            $query->published();
        }])
        ->orderBy('posts_count', 'desc')
        ->paginate(20);

        return view('tags.index', compact('tags'));
    }

    /**
     * Display posts for a specific tag
     */
    public function show(Tag $tag)
    {
        $posts = $tag->posts()
                     ->published()
                     ->with(['category', 'user', 'tags'])
                     ->latest('published_at')
                     ->paginate(10);

        // Get related tags (tags that appear with this tag)
        $relatedTags = Tag::whereHas('posts', function ($query) use ($tag) {
            $query->whereHas('tags', function ($q) use ($tag) {
                $q->where('tags.id', $tag->id);
            });
        })
        ->where('id', '!=', $tag->id)
        ->withCount('posts')
        ->orderBy('posts_count', 'desc')
        ->limit(10)
        ->get();

        return view('tags.show', compact('tag', 'posts', 'relatedTags'));
    }
}
