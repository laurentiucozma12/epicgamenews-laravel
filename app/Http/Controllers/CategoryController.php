<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->loadNavbarData();
    }
    
    public function index()
    {
        return view('categories.index', [
            'categories' => Category::withCount('posts')->paginate(12)
        ]);
    }

    public function show(Category $category)
    {
        $recent_posts = Post::latest()->take(5)->get();
        $categories = Category::withCount('posts')->orderBy('posts_count', 'desc')->take(10)->get();
        $tags = Tag::latest()->take(50)->get();

        return view('categories.show', [
            'category' => $category,
            'posts' => $category->posts()->paginate(5),
            'recent_posts' => $recent_posts,
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }
}
