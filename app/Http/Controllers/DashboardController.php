<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Statistics
        $totalPosts = Post::all()->count();
        $publishedPosts = Post::all()->where('status', 'published')->count();
        $totalViews = Post::all()->sum('views');
        $totalComments = Comment::whereHas('post', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();

        // Recent posts (last 5)
        $recentPosts = Post::with(['category', 'user', 'tags'])
            ->latest()
            ->take(5)
            ->get();

        // // Recent comments on user's posts
        $recentComments = Comment::whereHas('post', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['user', 'post'])
            ->latest()
            ->take(5)
            ->get();

        // Popular posts
        $popularPosts = Post::with(['category', 'user', 'tags'])
            ->where('status', 'published')
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        // Categories with post count
        $categoriesWithCount = Category::withCount(['posts' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->having('posts_count', '>', 0)
            ->get();

        // Writing activity for last 7 days
        $writingActivity = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = Post::with(['category', 'user', 'tags'])
                ->whereDate('created_at', $date)
                ->count();
            $writingActivity->put($date->format('D'), $count);
        }

        return view('dashboard', compact(
            'totalPosts',
            'publishedPosts',
            'totalViews',
            'totalComments',
            'recentPosts',
            'recentComments',
            'popularPosts',
            'categoriesWithCount',
            'writingActivity'
        ));
    }
}
