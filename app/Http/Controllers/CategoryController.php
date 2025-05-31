<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of all categories
     */
    public function index()
    {
        $categories = Category::withCount(['posts' => function ($query) {
            $query->published();
        }])
        ->orderBy('name')
        ->paginate(12);

        return view('categories.index', compact('categories'));
    }

    /**
     * Display posts for a specific category
     */
    public function show(Category $category)
    {
        $posts = $category->posts()
                         ->published()
                         ->with(['user', 'tags'])
                         ->latest('published_at')
                         ->paginate(10);

        // Get subcategories if you have them
        // $subcategories = $category->children;

        return view('categories.show', compact('category', 'posts'));
    }
}
