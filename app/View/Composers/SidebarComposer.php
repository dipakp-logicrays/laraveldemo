<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Post;

class SidebarComposer
{
    public function compose(View $view)
    {
        $view->with('sidebarCategories', $this->getCategories());
        $view->with('sidebarTags', $this->getTags());
        $view->with('recentPosts', $this->getRecentPosts());
    }

    private function getCategories()
    {
        return Category::withCount(['posts' => function ($query) {
            $query->published();
        }])
        ->having('posts_count', '>', 0)
        ->orderBy('name')
        ->get();
    }

    private function getTags()
    {
        return Tag::withCount(['posts' => function ($query) {
            $query->published();
        }])
        ->having('posts_count', '>', 0)
        ->orderBy('posts_count', 'desc')
        ->limit(10)
        ->get();
    }

    private function getRecentPosts()
    {
        return Post::published()
            ->latest('published_at')
            ->limit(5)
            ->get();
    }
}
