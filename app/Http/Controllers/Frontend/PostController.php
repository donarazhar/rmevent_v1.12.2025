<?php
// app/Http/Controllers/Frontend/PostController.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;

class PostController extends Controller
{
    /**
     * Display posts listing (Blog)
     */
    public function index()
    {
        $query = Post::published()->with(['author', 'category']);

        // Filter by category
        if (request('category')) {
            $query->whereHas('category', function ($q) {
                $q->where('slug', request('category'));
            });
        }

        // Filter by tag
        if (request('tag')) {
            $query->withAnyTags([request('tag')]);
        }

        // Search
        if (request('search')) {
            $query->search(request('search'));
        }

        // Sorting
        $sort = request('sort', 'latest');
        switch ($sort) {
            case 'latest':
                $query->orderBy('published_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('published_at', 'asc');
                break;
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
        }

        $posts = $query->paginate(12);

        // Get featured posts for sidebar
        $featuredPosts = Post::published()
            ->featured()
            ->take(5)
            ->get();

        // Get categories for filter
        $categories = Category::active()
            ->ofType(Category::TYPE_POST)
            ->withCount('posts')
            ->ordered()
            ->get();

        // Get popular tags
        $popularTags = Tag::popular(15)->get();

        // Get recent posts for sidebar
        $recentPosts = Post::published()
            ->recent(5)
            ->get();

        return view('frontend.posts.index', compact(
            'posts',
            'featuredPosts',
            'categories',
            'popularTags',
            'recentPosts'
        ));
    }

    /**
     * Display post detail
     */
    public function show($slug)
    {
        $post = Post::published()
            ->with(['author', 'category', 'tags', 'media'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment views
        $post->incrementViews();

        // Log activity
        if (auth()->check()) {
            $post->logActivity('viewed', 'Viewed post: ' . $post->title);
        }

        // Get related posts
        $relatedPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->take(3)
            ->get();

        // Get previous and next post
        $previousPost = Post::published()
            ->where('published_at', '<', $post->published_at)
            ->orderBy('published_at', 'desc')
            ->first();

        $nextPost = Post::published()
            ->where('published_at', '>', $post->published_at)
            ->orderBy('published_at', 'asc')
            ->first();

        return view('frontend.posts.show', compact(
            'post',
            'relatedPosts',
            'previousPost',
            'nextPost'
        ));
    }

    /**
     * Display posts by category
     */
    public function category($slug)
    {
        $category = Category::active()
            ->ofType(Category::TYPE_POST)
            ->where('slug', $slug)
            ->firstOrFail();

        $posts = Post::published()
            ->where('category_id', $category->id)
            ->with(['author', 'category'])
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('frontend.posts.category', compact('category', 'posts'));
    }

    /**
     * Display posts by tag
     */
    public function tag($slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $posts = Post::published()
            ->withAnyTags([$tag->id])
            ->with(['author', 'category'])
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('frontend.posts.tag', compact('tag', 'posts'));
    }
}