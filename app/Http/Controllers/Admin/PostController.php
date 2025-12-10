<?php
// app/Http/Controllers/Admin/PostController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display posts listing
     */
    public function index(Request $request)
    {
        $query = Post::with(['author', 'category']);

        // Search
        if ($request->search) {
            $query->search($request->search);
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by author
        if ($request->author_id) {
            $query->where('author_id', $request->author_id);
        }

        // Filter by date range
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $posts = $query->paginate(20);

        // Get categories for filter
        $categories = Category::ofType(Category::TYPE_POST)->ordered()->get();

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $categories = Category::active()
            ->ofType(Category::TYPE_POST)
            ->ordered()
            ->get();

        $tags = Tag::orderBy('name')->get();

        return view('admin.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store new post
     */
    public function store(PostRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            
            // Generate slug
            $data['slug'] = Str::slug($data['title']);
            $data['author_id'] = auth()->id();

            // Calculate reading time
            $data['reading_time'] = $this->calculateReadingTime($data['content']);

            // Handle featured image
            if ($request->hasFile('featured_image')) {
                $path = $request->file('featured_image')->store('posts', 'public');
                $data['featured_image'] = $path;
            }

            // Set published_at if status is published
            if ($data['status'] === Post::STATUS_PUBLISHED && !isset($data['published_at'])) {
                $data['published_at'] = now();
            }

            $post = Post::create($data);

            // Attach tags
            if ($request->has('tags')) {
                $post->syncTags($request->tags);
            }

            // Handle gallery images
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $image) {
                    $post->addMedia($image, 'gallery');
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.posts.index')
                ->with('success', 'Post berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show edit form
     */
    public function edit(Post $post)
    {
        $post->load(['category', 'tags', 'media']);

        $categories = Category::active()
            ->ofType(Category::TYPE_POST)
            ->ordered()
            ->get();

        $tags = Tag::orderBy('name')->get();

        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update post
     */
    public function update(PostRequest $request, Post $post)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            // Update slug if title changed
            if ($data['title'] !== $post->title) {
                $data['slug'] = Str::slug($data['title']);
            }

            // Calculate reading time
            $data['reading_time'] = $this->calculateReadingTime($data['content']);

            // Handle featured image
            if ($request->hasFile('featured_image')) {
                // Delete old image
                if ($post->featured_image) {
                    \Storage::disk('public')->delete($post->featured_image);
                }
                $path = $request->file('featured_image')->store('posts', 'public');
                $data['featured_image'] = $path;
            }

            // Set published_at if status changed to published
            if ($data['status'] === Post::STATUS_PUBLISHED && 
                $post->status !== Post::STATUS_PUBLISHED && 
                !$post->published_at) {
                $data['published_at'] = now();
            }

            $post->update($data);

            // Sync tags
            if ($request->has('tags')) {
                $post->syncTags($request->tags);
            }

            // Handle gallery images
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $image) {
                    $post->addMedia($image, 'gallery');
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.posts.index')
                ->with('success', 'Post berhasil diupdate.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete post
     */
    public function destroy(Post $post)
    {
        try {
            // Delete featured image
            if ($post->featured_image) {
                \Storage::disk('public')->delete($post->featured_image);
            }

            // Delete all media
            $post->clearMediaCollection();

            $post->delete();

            return redirect()
                ->route('admin.posts.index')
                ->with('success', 'Post berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $action = $request->action;
        $postIds = $request->post_ids;

        if (!$postIds || !is_array($postIds)) {
            return redirect()
                ->back()
                ->with('error', 'Tidak ada post yang dipilih.');
        }

        try {
            switch ($action) {
                case 'publish':
                    Post::whereIn('id', $postIds)->update([
                        'status' => Post::STATUS_PUBLISHED,
                        'published_at' => now()
                    ]);
                    $message = 'Post berhasil dipublish.';
                    break;

                case 'draft':
                    Post::whereIn('id', $postIds)->update(['status' => Post::STATUS_DRAFT]);
                    $message = 'Post berhasil diubah ke draft.';
                    break;

                case 'delete':
                    Post::whereIn('id', $postIds)->delete();
                    $message = 'Post berhasil dihapus.';
                    break;

                default:
                    return redirect()->back()->with('error', 'Aksi tidak valid.');
            }

            return redirect()
                ->route('admin.posts.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Calculate reading time in minutes
     */
    private function calculateReadingTime(string $content): int
    {
        $wordCount = str_word_count(strip_tags($content));
        $readingTime = ceil($wordCount / 200); // Average 200 words per minute
        return max(1, $readingTime);
    }
}