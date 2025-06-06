<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with(['category', 'user', 'tags'])
                    ->published()
                    ->latest('published_at')
                    ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published',
        ]);

        // Handle file upload
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('posts', 'public');
            $validated['featured_image'] = $path;
        }

        // Set additional fields
        $validated['user_id'] = auth()->id();

        if ($request->status === 'published') {
            $validated['published_at'] = now();
        }

        // Create the post
        $post = Post::create($validated);

        // Attach tags
        if ($request->has('tags')) {
            $post->tags()->attach($request->tags);
        }

        return redirect()->route('posts.show', $post)
                         ->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // Only show published posts to guests
        if (!auth()->check() && !$post->isPublished()) {
            abort(404);
        }

        // Increment views for published posts
        if ($post->isPublished()) {
            $post->incrementViews();
        }

        // Load relationships
        $post->load(['category', 'user', 'tags']);

        // Get related posts
        $relatedPosts = Post::where('category_id', $post->category_id)
                            ->where('id', '!=', $post->id)
                            ->published()
                            ->limit(3)
                            ->get();

        return view('posts.show', compact('post', 'relatedPosts'));
    }

    /**
     * Show the form for editing the post
     */
    public function edit(Post $post)
    {
        // Authorization check
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $categories = Category::all();
        $tags = Tag::all();
        $postTags = $post->tags->pluck('id')->toArray();

        return view('posts.edit', compact('post', 'categories', 'tags', 'postTags'));
    }

    /**
     * Update the specified post
     */
    public function update(Request $request, Post $post)
    {
        // Authorization check
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published',
        ]);

        // Handle file upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }

            $path = $request->file('featured_image')->store('posts', 'public');
            $validated['featured_image'] = $path;
        }

        // Handle publishing
        if ($request->status === 'published' && !$post->published_at) {
            $validated['published_at'] = now();
        } elseif ($request->status === 'draft') {
            $validated['published_at'] = null;
        }

        // Update the post
        $post->update($validated);

        // Sync tags
        $post->tags()->sync($request->tags ?? []);

        return redirect()->route('posts.show', $post)
                         ->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified post
     */
    public function destroy(Post $post)
    {
        // Authorization check
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete featured image
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        // Delete the post (tags will be detached automatically due to cascade)
        $post->delete();

        return redirect()->route('posts.index')
                         ->with('success', 'Post deleted successfully!');
    }

    /**
     * Display user's posts
     */
    public function myPosts()
    {
        $posts = Post::where('user_id', auth()->id())
                     ->with(['category', 'tags'])
                     ->latest()
                     ->paginate(10);

        return view('posts.my-posts', compact('posts'));
    }

    /**
     * Search posts
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        $posts = Post::where(function($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                          ->orWhere('content', 'like', "%{$query}%");
                     })
                     ->published()
                     ->with(['category', 'user', 'tags'])
                     ->paginate(10)
                     ->withQueryString();

        return view('posts.search', compact('posts', 'query'));
    }
}
